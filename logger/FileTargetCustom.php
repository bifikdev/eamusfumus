<?php

namespace app\logger;

use yii\log\FileTarget;
use yii\log\Logger;

class FileTargetCustom extends FileTarget
{

    /**
     * @param array $message
     * @return string
     */
    public function formatMessage($message)
    {
        list($text, $level, $category, $timestamp) = $message;
        $level = Logger::getLevelName($level);
        $time = $this->getTime($timestamp);
        $text = (string)$text;
        return "[{$time}][{$level}][{$category}] $text";

    }
}