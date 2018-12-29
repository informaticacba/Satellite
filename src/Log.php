<?php

namespace Satellite;

class Log
{
    /**
     * Layer enter handler
     * @param Request $request
     * @return Request|Response
     */
    public static function enter(Request $request){
        $log = sprintf(
            '%s %s %s "%s" %s',
            $request->getIp(),
            $request->getMethod(),
            $request->getUri(),
            $request->getUserAgent(),
            $request->getReferer()
        );

        self::write($log);
        return $request;
    }

    /**
     * Write log
     * @param string $string
     */
    public static function write($string)
    {
        $fileName = 'log_' . date('Ymd');
        $string = '[' . date('Y-m-d H:i:s O') . ']:' . $string . PHP_EOL;
        file_put_contents(LOG_PATH . '/' . $fileName, $string, FILE_APPEND);
    }

    /**
     * Write error log
     * @param string $string
     */
    public static function error($string){
        $fileName = 'error_' . date('Ymd');
        $string = '[' . date('Y-m-d H:i:s O') . ']:' . $string . PHP_EOL;
        file_put_contents(LOG_PATH . '/' . $fileName, $string, FILE_APPEND);
    }

    /**
     * Set error log handler
     */
    public static function setErrorHandler(){
        set_error_handler(function($errno, $errstr, $errfile, $errline){
            Log::error($errstr);
        });
    }

    /**
     * Layer leave handler
     * @param Response $response
     * @return Response
     */
    public static function leave(Response $response){
        return $response;
    }
}
