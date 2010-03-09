<?php
class PoShell extends Shell {
    var $tasks = array('PoMerge');

    /**
     * startup
     *
     * @params
     * @return
     */
    function startup(){

    }

    /**
     * main
     *
     * @param
     * @return
     */
    function main() {
        $this->out(__('Po Edit', true));
        $this->hr();
        $this->out(__('[M]erge POT file', true));
        $this->out(__('[Q]uit', true));

        $choice = strtoupper($this->in(__('What would you like to do?', true), array('M', 'Q')));
        switch ($choice) {
        case 'M':
            $this->PoMerge->execute();
            break;
        case 'Q':
            exit(0);
            break;
        default:
            $this->out(__('You have made an invalid selection. Please choose a command to execute by entering M or Q.', true));
        }
        $this->hr();
        $this->main();
    }

    /**
     * merge
     *
     * @param
     * @return
     */
    function merge(){
        $this->PoMerge->execute();
    }

    /**
     * help
     *
     * @param
     * @return
     */
    function help() {
        $this->out('CakePHP Po Edit');
        $this->hr();
        $this->out('The Bake script generates controllers, views and models for your application.');
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