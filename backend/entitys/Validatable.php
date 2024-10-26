<?php

namespace Palmo\entitys;

trait Validatable
{
    public function log($message):void
    {
        $filename = '../logs/validate' . date('Y-m-d') . '.log';
        file_put_contents($filename, date('H:i:s'). ' - ' . $message . PHP_EOL, FILE_APPEND);
    }
}