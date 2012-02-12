<?php
App::uses('Shell', 'Console');

class PoShell extends Shell {

    public $tasks = array('Po.PoMerge');

    /**
     * startup
     *
     * @return
     */
    public function startup(){
        parent::startup();
    }

    /**
     * main
     *
     * @return
     */
    public function main() {
        $this->out(__d('cake_console', 'Po Edit'));
        $this->hr();
        $this->out(__d('cake_console', '[M]erge POT file'));
        $this->out(__d('cake_console', '[Q]uit'));

        $choice = strtoupper($this->in(__d('cake_console', 'What would you like to do?'), array('M', 'Q')));
        switch ($choice) {
        case 'M':
            $this->PoMerge->execute();
            break;
        case 'Q':
            exit(0);
            break;
        default:
            $this->out(__d('cake_console', 'You have made an invalid selection. Please choose a command to execute by entering M or Q.'));
        }
        $this->hr();
        $this->main();
    }

    /**
     * merge
     *
     * @return
     */
    public function merge(){
        $this->PoMerge->execute();
    }

    /**
     * help
     *
     * @param
     * @return
     */
    public function help() {
        $this->out('CakePHP Po Edit');
        $this->hr();
        $this->out('CakePHP .po File Edit Plugin');
        $this->hr();
        $this->out("Usage: cake po <command> <arg1> <arg2>...");
        $this->hr();
        $this->out('Params:');
        $this->out("\t-created <path> Absolute path to created pot file.\n");
        $this->out("\t-create <path> Absolute path to current po file (your modified file).\n");
        $this->out('Commands:');
        $this->out("\n\tpo help\n\t\tshows this help message.");
        $this->out("");
    }
  }