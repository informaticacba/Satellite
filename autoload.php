<?php

require_once __DIR__ . '/src/Utility.php';

use Satellite\Utility;

spl_autoload_register(function ($name) {
    $name = explode('\\', $name);
    $file = __DIR__ . '/src/' . $name[count($name) - 1] . '.php';
    if (file_exists($file)) {
        require_once($file);
    }
});

Utility::load(__DIR__ . '/src', array('TestCase.php'));
