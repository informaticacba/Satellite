<?php

namespace Satellite;

class App
{
    /**
     * Application layers
     */
    private static $layers = array();

    /**
     * Set layers
     * @param array $layers array of Layer class names
     */
    public static function setLayers($layers)
    {
        self::$layers = $layers;
    }

    /**
     * Initialize application
     * @param string $initializeDir path to initialize directory
     */
    public static function init($bootFile)
    {
        $boot = require_once $bootFile;
        $next = $boot();
        $bootDir = preg_replace('/\/[0-9a-zA-Z-_]*\.php$/', '', $bootFile);

        while($next != null){
            $boot = require_once $bootDir . '/' . $next;
            $next = $boot();
        }

        if(!defined('ENV')){
            define('ENV', 'local');   
        }
        if(!defined('DATABASE_PATH')){
            define('DATABASE_PATH', APP_ROOT . '/database/database.sqlite');   
        }
        if(!defined('VIEWS_PATH')){
            define('VIEWS_PATH', APP_ROOT . '/resources/views');
        }
        if(!defined('LOG_PATH')){
            define('LOG_PATH', APP_ROOT . '/logs');
        }
    }

    /**
     * Handle request
     * @param Request $request
     */
    public static function handle(Request $request)
    {
        $count = count(self::$layers);
        $isBreak = false;
        $i = 0;
        for(; $i < $count; $i++) {
            $middlewareClassName = self::$layers[$i];
            $request = $middlewareClassName::enter($request);
            if($request instanceof Response){
                $isBreak = true;
                $i++;
                break;
            }
        }

        if ($isBreak) {
            $response = $request;
        }else{
            $className = $request->controllerClass;
            $controllerInstance = new $className();
            $method = $request->controllerMethod;
            $response = $controllerInstance->$method($request);
        }

        for($i--; $i >= 0; $i--) {
            $middlewareClassName = self::$layers[$i];
            $response = $middlewareClassName::leave($response);
        }
        return $response;
    }
}
