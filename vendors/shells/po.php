<?php
class PoShell extends Shell {
    var $tasks = array('PoMerge');

    /**
     * startup
     * startup
     *
     * @params
     * @return
     */
    function startup(){

    }

    /**
     * main
     * main
     *
     * @params
     * @return
     */
    function main() {
        $this->out(__('Po Edit Shell', true));
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
  }
?>
