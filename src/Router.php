<?php

namespace Satellite;

class Router implements Layer
{
    private static $methods = array('GET', 'POST', 'PUT', 'DELETE');
    private static $route = array();

    /**
     * @param string $method HTTP method (GET|POST|PUT|DELETE)
     * @param string $path
     * @param string $controllerClass
     * @param string $controllerMethod
     */
    public static function set($method, $path, $controllerClass, $controllerMethod)
    {
        if(!in_array($method, self::$methods)){
            return;
        }
        if(!array_key_exists($method, self::$route)){
            self::$route[$method] = array($path => array($controllerClass, $controllerMethod));
        }else{
            self::$route[$method] = array_merge(self::$route[$method], array($route => $controllerClassName . '$' . $methodName));
        }
    }

    /**
     * Layer enter handler
     * @param Request $request
     * @return Request|Response
     */
    public static function enter(Request $request){
        $requestUri = explode('/', $request->getUri());

        $uriVariables = array();
        $routes = self::$route[$request->getMethod()];

        $finalAction;
        foreach ($routes as $route => $action) {
            $routeUri = explode('/', $route);

            if (count($requestUri) != count($routeUri)) {
                continue;
            }

            if ($requestUri == $routeUri) {
                return self::handle($request, $action, $uriVariables);
            }

            $isContinue = false;
            for ($i = 0; $i < count($requestUri); $i++) {
                if (strpos($routeUri[$i], '^') !== false) {
                    $uriVariables[ltrim($routeUri[$i], '^')] = $requestUri[$i];
                } else {
                    if ($requestUri[$i] != $routeUri[$i]) {
                        $isContinue = true;
                    }
                }
            }
            if ($isContinue) {
                continue;
            }
            return self::handle($request, $action, $uriVariables);
        }
        return new Response(404);
    }

    /**
     * Add action to request
     * @param Request $request
     * @param string $action
     * @param array $uriVariables
     */
    private static function handle(Request $request, $action, $uriVariables)
    {
        $controllerClass = $action[0];
        $controllerMethod = $action[1];
        $request->controllerClass = $controllerClass;
        $request->controllerMethod = $controllerMethod;
        $request->uriVariables = $uriVariables;
        return $request;
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
