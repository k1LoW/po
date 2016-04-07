<?php

namespace Po\Shell\Task;

use Cake\Cache\Cache;
use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Filesystem\File;
use Gettext\Translations;

class MergeTask extends Shell
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
        $default = APP . 'Locale' . DS . 'default.pot';
        $response = $this->in("What is the full path you would like to merge file (created pot file)?\nExample:"
                              . $default
                              . "\n[Q]uit", null, $default);
        if (strtoupper($response) === 'Q') {
            $this->out('Merge Aborted');
            $this->_stop();
        }
        $created = new File($response, false, 0755);
        if (!$created->exists()) {
            $this->err('The file path you supplied was not found. Please try again.');
            $this->_stop();
        }
        $default = APP . 'Locale' . DS . 'ja' . DS . 'default.po';
        $response = $this->in("What is the full path you would like to merge file (current po file)?\nExample: "
                              . $default
                              . "\n[Q]uit", null, $default);
        if (strtoupper($response) === 'Q') {
            $this->out('Merge Aborted');
            $this->_stop();
        }
        $current = new File($response, false, 0755);
        if (!$current->exists()) {
            $this->err('The file path you supplied was not found. Please try again.');
            $this->_stop();
        }
        $createdTranslations = Translations::fromPoFile($created->path);
        $createdTranslations->addFromPoFile($current->path);
        $merged = $createdTranslations->toPoString();
        $this->createFile($current->path, $merged);
    }
}
