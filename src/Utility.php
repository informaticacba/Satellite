<?php

namespace Satellite;

class Utility
{
    /**
     * Load and get class names array in the directory
     * @param string $dir target directory path
     * @return array class names array
     */
    public static function loadClass($dir)
    {
        $classNames = array();
        foreach (scandir($dir) as $file) {
            if (in_array($file, array('.', '..')) || is_dir($dir . '/' . $file) || !preg_match('/\.php$/', $file)) {
                continue;
            }
            require_once $dir . '/' . $file;
            $className = preg_replace('/\.php$/', '', $file);
            array_push($classNames, $className);
        }
        return $classNames;
    }

    /**
     * Load php file in the directory
     * @param $dir target directory path
     * @param 
     */
    public static function load($dir, $ignore = array())
    {
        $ignore = array_merge(array('.', '..'), $ignore);
        foreach (scandir($dir) as $file) {
            if (in_array($file, $ignore)) {
                continue;
            }
            if (is_dir($dir . '/' . $file)) {
                self::load($dir . '/' . $file);
            }
            if (!preg_match('/.*\.php/', $file)) {
                continue;
            }
            require_once $dir . '/' . $file;
        }
    }
}
