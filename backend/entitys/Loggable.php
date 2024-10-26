<?php

namespace Palmo\entitys;

trait Loggable
{
    public function log($message):void
    {
        $filename = '../logs/users' . date('Y-m-d') . '.log';
        file_put_contents($filename, date('H:i:s'). ' - ' . $message . PHP_EOL, FILE_APPEND);
    }
}