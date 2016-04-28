<?php

namespace Po\Shell\Task;

use Cake\Datasource\ConnectionManager;
use Cake\Database\Schema\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Cake\Cache\Cache;
use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Filesystem\File;
use Gettext\Translations;
use Gettext\Translation;

class SchemaTask extends Shell
{
    public function startup()
    {
        Configure::write('debug', true);
        Cache::disable();
    }

    /**
     * main
     *
     */
    public function main(){
        $schemaPo = APP . 'Locale' . DS . 'schema.pot';
        $conn = ConnectionManager::get('default');
        $collection = $conn->schemaCollection();
        $translations = new Translations();
        $tables = $collection->listTables();
        foreach ($tables as $table) {
            $translations->insert($table, Inflector::humanize(Inflector::underscore($table)));
            $translations->insert($table, Inflector::humanize(Inflector::underscore(Inflector::singularize($table))));
            $columns = $collection->describe($table)->columns();
            foreach ($columns as $column) {
                $c = $collection->describe($table)->column($column);
                $comment = $c['comment'];
                $t = new Translation($table . '.' . $column, Inflector::humanize(Inflector::underscore($column)));
                $translations[] = $t;
                $t->setTranslation($comment);
                $t = new Translation($table . '.' . $column, Inflector::humanize(Inflector::underscore(Inflector::singularize($table))) . ' ' . Inflector::humanize(Inflector::underscore($column)));
                $translations[] = $t;
                $t->setTranslation($comment);
            }
        }
        $poString = $translations->toPoString();
        $caked = preg_replace('/msgctxt "([^"]+)"/i','#: \1', $poString);
        $this->createFile($schemaPo, $caked);
    }
}
