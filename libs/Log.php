<?php

class Log
{
    static $logFile = 'log.txt';
    static $logDir  = 'logs';
    static function init()
    {
        if(!file_exists(self::$logDir))
        {
            mkdir(self::$logDir);
        }

        $logFile = self::$logDir .'/'. self::$logFile;
        if(!file_exists($logFile))
        {
            file_put_contents($logFile, '');
        }
    }

    static function write($content)
    {
        self::init();
        $content = is_string($content) ? $content : json_encode($content);
        $content = date('Y-m-d H:i:s'). ' : ' . $content . "\n";

        $logFile = self::$logDir .'/'. self::$logFile;
        file_put_contents($logFile, $content, FILE_APPEND);
    }
}