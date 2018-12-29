<?php

namespace Satellite;

class Request
{
    /**
     * request
     */
    private $request = array();

    /**
     * request URI
     */
    private $uri;

    /**
     * request HTTP method
     */
    private $method;

    /**
     * user ip address
     */
    private $ip;

    /**
     * user agent
     */
    private $userAgent;

    /**
     * referer
     */
    private $referer;

    /**
     * controller class to handle request
     */
    public $controllerClass;

    /**
     * method of controller class to handle request
     */
    public $controllerMethod;

    /**
     * URI variables
     */
    public $uriVariables = array();

    /**
     * Create request instance
     */
    public function __construct($uri, $method, $request, $ip, $userAgent = null, $referer = null)
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->request = $request;
        $this->ip = $ip;
        $this->userAgent = $userAgent;
        $this->referer = $referer;
    }

    /**
     * Create reqeust from supre global variables
     */
    public static function createFromGlobal(){
        $uri = preg_replace('/\?.*$/', '', $_SERVER['REQUEST_URI']);
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST' && array_key_exists('__method', $_POST)) {
            if ($_POST['__method'] === 'PUT') {
                $method = 'PUT';
            } elseif ($_POST['__method'] === 'DELETE') {
                $method = 'DELETE';
            }
        }
        foreach ($_GET as $key => $value) {
            $request[$key] = $value;
        }
        foreach ($_POST as $key => $value) {
            $request[$key] = $value;
        }
        $ip = $_SERVER['REMOTE_ADDR'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $referer = $_SERVER['HTTP_REFERER'];
        return new Request($uri, $method, $request, $ip, $userAgent, $referer);
    }

    /**
     * Get request URI
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Get HTTP method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get IP address
     */
    public function getIp(){
        return $this->ip;
    }

    /**
     * Get user agent
     */
    public function getUserAgent(){
        return $this->userAgent;
    }

    /**
     * Get referer
     */
    public function getReferer(){
        return $this->referer;
    }

    /**
     * Get request parameters
     */
    public function get($key)
    {
        if ($key == null || $this->request == null || !array_key_exists($key, $this->request)) {
            return null;
        }
        return $this->request[$key];
    }

    /**
     * Get all request parameters
     */
    public function all()
    {
        return $this->request;
    }
}
