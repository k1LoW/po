<?php

App::uses('AppShell', 'Console/Command');
App::uses('BakeTask', 'Console/Command/Task');
App::uses('PoParser', 'Po.Lib');

class PoGenerateTask extends AppShell{

    public $tasks = array('DbConfig', 'Model');

    public $connection = null;

    public function execute() {
        App::uses('CakeSchema', 'Model');

        if (isset($this->params['root'])) {
            $root = $this->params['root'];
        } else {
            $root = ROOT;
        }

        if (empty($this->connection)) {
            $this->connection = $this->DbConfig->getConfig();
        }
        $tables = $this->Model->getAllTables($this->connection);
        $this->_Schema = new CakeSchema();
        $data = $this->_Schema->read(array('models' => false, 'connection' => $this->connection));

        $pot = array();
        foreach ($data as $type => $key) {
            if ($type !== 'tables') {
                continue;
            }
            foreach ($key as $table => $fields) {
                foreach ($fields as $field => $v) {
                    if (empty($pot[$field])) {
                        $pot[$field] = array(
                            'comments' => '#: ' . $table . '.' .$field . "\n",
                            'msgid' => Inflector::humanize(Inflector::underscore($field)),
                            'msgstr' => '',
                        );
                    } else {
                        $pot[$field]['comments'] .= '#: ' . $table . '.' .$field . "\n";
                    }
                }
            }
        }

        $header = '"Project-Id-Version: PROJECT VERSION\n"
"POT-Creation-Date: 2015-01-07 14:21+0900\n"
"PO-Revision-Date: YYYY-mm-DD HH:MM+ZZZZ\n"
"Last-Translator: NAME <EMAIL@ADDRESS>\n"
"Language-Team: LANGUAGE <EMAIL@ADDRESS>\n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=utf-8\n"
"Content-Transfer-Encoding: 8bit\n"
"Plural-Forms: nplurals=INTEGER; plural=EXPRESSION;\n"';

        $out = PoParser::generatePoFile($pot, $header);

        $response = '';
        $default = $root . DS . "app" . DS . "Locale" . DS . "schema.pot";
        $example = $default;
        while ($response == '') {
            $response = $this->in("What is the full path you would like to generate schema.pot ?\nExample: "
            . $default
            . "\n[Q]uit", null, $example);
            if (strtoupper($response) === 'Q') {
                $this->out('Generate Aborted');
                $this->_stop();
            }
        }

        $this->createFile($response, $out);
        $this->out('Done.');
    }

}
