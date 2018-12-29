<?php

namespace Satellite;

class Session implements Layer
{
    /**
     * Layer enter handler
     * @param Request $request
     * @return Request|Response
     */
    public static function enter(Request $request)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        static::clear('__flash');
        if (static::get('__flash__new')) {
            $flash = $_SESSION['__flash__new'];
            $_SESSION['__flash'] = $flash;
            static::clear('__flash__new');
        }
        return $request;
    }

    /**
     * Layer leave handler
     * @param Response $response
     * @return Response
     */
    public static function leave(Response $response){
        session_write_close();
        return $response;
    }

    /**
     * Get session value
     * @param string $key
     */
    public static function get($key)
    {
        $value = null;
        if (array_key_exists($key, $_SESSION)) {
            $value = $_SESSION[$key];
        }
        return $value;
    }

    /**
     * Set session value
     * @param string $key
     * @param mixed $value
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] =  $value;
    }

    /**
     * Unset session value
     * @param string $key
     */
    public static function clear($key)
    {
        if (static::get($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Set flash session data
     * @param string $key
     * @param mixed $value
     */
    public static function setFlash($key, $value)
    {
        if (array_key_exists('__flash__new', $_SESSION)) {
            $_SESSION['__flash__new'] = array_merge($_SESSION['__flash__new'], array($key => $value));
        }else{
            $_SESSION['__flash__new'] = array($key => $value);
        }
    }

    /**
     * Get flash session data
     * @param string $key
     * @param mixed $value
     */
    public static function getFlash(){
        $value = static::get('__flash');
        if ($value != null && array_key_exists($key, $value)) {
            $value = $value[$key];
        }
        return $value;
    }
}
