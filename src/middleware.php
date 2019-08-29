<?php

//use Psr\Http\Message\ServerRequestInterface as Request;
//use Psr\Http\Message\ResponseInterface as Response;

use \Slim\Http\Request;
use \Slim\Http\Response;

$auth = function (Request $request, Response $response, $next) {
    if (isset($_SESSION['permission']) && $_SESSION['permission'] == true) {
        $response = $next($request, $response);
    } else {
        $response = $response->withRedirect('/login');
    }
    return $response;
};
