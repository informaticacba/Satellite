<?php

namespace Satellite;

class Response{

    /**
     * response HTTP status code
     */
    public $status;

    /**
     * response redirect path
     */
    public $redirectPath;

    /**
     * response header
     */
    public $headers = array();

    /**
     * response body
     */
    public $body;

    /**
     * flag in the view
     */
    private $isView = false;

    /**
     * @param int $statusCode
     */
    public function __construct($statusCode = 200)
    {
        $this->status = $statusCode;
    }

    /**
     * Set header
     * @param string string string to set header
     */
    public function header($string){
        array_push($this->headers, $string);
    }

    /**
     * Set redirect path
     * @param string $path path to redirect
     */
    public function redirect($path){
        $this->status = 302;
        $this->redirectPath = $path;
        array_push($this->headers, 'Location: ' . $path);
        return $this;
    }

    /**
     * Send response
     */
    public function send(){
        foreach($this->headers as $header){
            header($header, true);
        }
        if (!headers_sent()) {
            header('HTTP/1.1 ' . $this->status);
        }
        echo $this->body;
    }

    /**
     * Load view file
     * @param string $path path to the view file
     * @param array $data data to use view file
     */
    public function view($path, $data = null){
        if (isset($data)) {
            foreach ($data as $key => $value) {
                $$key = $value;
            }
        }
        unset($data);

        ob_start();
        if($this->isView){
            require VIEWS_PATH . $path;
            echo ob_get_clean();
        }else{
            $this->isView = true;
            require VIEWS_PATH . $path;
            $this->body = ob_get_clean();
            $this->isView = false;
            return $this;
        }
    }
}