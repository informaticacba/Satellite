<?php

require_once __DIR__ . '/autoload.php';

define('APP_ROOT', getcwd());
use Satellite\App;
use Satellite\DB;

$command = count($argv) >= 2 ? $argv[1] : '';
$action = count($argv) >= 3 ? $argv[2] : '';

App::init('bootstraps/bootstrap.php');
if($command === 'db'){
    $pdoArg = DATABASE_TYPE . ':' . APP_ROOT . '/' . DATABASE_NAME;
    if($action === 'up'){
        DB::connect($pdoArg);
        $targets = Utility::loadClass(APP_ROOT . '/database/migrations');
        DB::$pdo->beginTransaction();
        foreach($targets as $className){
            $className::up();
            var_dump($className);
        }
        DB::$pdo->commit();
        print_r(DB::$pdo->errorInfo());
        return;
    }else if($action === 'destroy'){
        DB::connect($pdoArg);
        DB::destroy();
        print_r(DB::$pdo->errorInfo());
    }
}