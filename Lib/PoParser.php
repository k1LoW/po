<?php

class PoParser {

    /**
     * parsePoFile
     *
     */
    public static function parsePoFile($filePath){
        $fp = fopen($filePath,"r");
        $parsed = array(
            'header' => '',
            'contents' => array()
        );
        $current_comment = '';
        $msgid = '';
        if($fp){
            while(!feof($fp)) {
                $line = preg_replace('/\n/', '', fgets($fp));
                if (preg_match('/^(#:.*)$/',$line,$matches)) {
                    //file comments
                    $current_comment .= $matches[1] . "\n";
                } elseif (preg_match('/^msgid "(.*)"$/',$line,$matches)) {
                    //msgid
                    $msgid = $matches[1];
                    $parsed['contents'][$msgid]['msgid'] = $msgid;
                } elseif (preg_match('/^msgstr "(.*)"$/',$line,$matches)) {
                    //msgstr
                    $parsed['contents'][$msgid]['msgstr'] = $matches[1];
                    $parsed['contents'][$msgid]['comments'] = $current_comment;
                    $current_comment = '';
                } elseif (preg_match('/^(".+)$/',$line,$matches)) {
                    //header
                    $parsed['header'] .= $matches[1] . "\n";
                }
            }
            fclose($fp);
        }
        return $parsed;
    }

}