<?php

namespace Satellite;

use RuntimeException;
use PHPUnit_Framework_Assert as PHPUnit;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $host = 'http://127.0.0.1';

    /**
     * Set up database
     */
    public function setUp()
    {
        $pdoArg = DATABASE_TYPE . ':' . APP_ROOT . '/' . DATABASE_NAME;
        DB::connect($pdoArg);
        DB::destroy();
        $targets = Utility::loadClass(APP_ROOT . '/database/migrations');
        DB::$pdo->beginTransaction();
        foreach($targets as $className){
            $className::up();
        }
        DB::$pdo->commit();
        $_SESSION = array();
    }

    public function tearDown(){
        unset($_SESSION);
    }

    public function get($path)
    {
        return App::handle(Request::create('GET', $path));
    }

    public function post($path, $data)
    {
        return App::handle(Request::create('POST', $path, $data));
    }

    public function assertStatus($response, $code)
    {
        PHPUnit::assertEquals($response->status, $code);
    }

    public function assertRedirect($response, $path)
    {
        $this->assertStatus($response, 302);
        PHPUnit::assertEquals($response->redirectPath, $path);
    }

    public function assertSee($response, $str){
        $this->assertRegExp('/'. $str .'/', $response->body);
    }
}
