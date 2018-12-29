<?php

namespace Satellite;

use PDO;

class DB implements Layer
{
    public static $pdo;

    /**
     * Layer enter handler
     * @param Request $request
     * @return Request|Response
     */
    public static function enter(Request $request)
    {
        $pdoArg = 'sqlite:' . DATABASE_PATH;
        DB::connect($pdoArg);
        DB::$pdo->beginTransaction();
        return $request;
    }

    /**
     * Layer leave handler
     * @param Response $response
     * @return Response
     */
    public static function leave(Response $response){
        DB::$pdo->commit();
        return $response;
    }

    /**
     * Initialize PDO
     * @param string $pdoArg
     */
    public static function connect($pdoArg)
    {
        static::$pdo = new PDO($pdoArg);
        static::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        static::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Drop all tables
     */
    public static function destroy()
    {
        $tables = static::$pdo
            ->query('SELECT name FROM sqlite_master;')
            ->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_UNIQUE);

        foreach ($tables as $table => $v) {
            static::$pdo->exec('DROP TABLE ' . $table);
        }
    }

    /**
     * Exec sql
     */
    public static function exec($sql){
        $result = DB::$pdo->exec($sql);
    }
}
