<?php

namespace worstinme\zoo\helpers;

use Yii;
use yii\base\InvalidParamException;

class AppHelper
{
	public static function findDirectories($dir) {

        $list = [];

        if (is_dir($dir)) {
            
            $handle = opendir($dir);
            if ($handle === false) {
                throw new InvalidParamException("Unable to open directory: $dir");
            }

            
            
            while (($file = readdir($handle)) !== false) {
                $path = $dir . DIRECTORY_SEPARATOR . $file;
                if ($file !== '.' && $file !== '..' && is_dir($path)) {
                    $list[] = $file;
                }
            }

        }

        return $list;
	}
}