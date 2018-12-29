<?php

namespace Satellite;

interface Layer{

    public static function enter(Request $request);
    public static function leave(Response $response);
}