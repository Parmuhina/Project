<?php

use App\Services\Redirect;
use Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Views\Template;

require 'vendor/autoload.php';

session_start();

$dotenv = Dotenv::createMutable(__DIR__);
$dotenv->load();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $route) {
   // $route->addRoute('GET', '/search', ['App\Controller\NewsController', 'index']);
    $route->addRoute('GET', '/', ['App\Controllers\RequestController', 'getTemplateRequest']);

});

$loader = new FilesystemLoader('Views/Templates');
$view=new Environment($loader);

/**
$authorisationTemplateVariables=[
    AuthorisationTemplateVariables::class,
    Errors::class
];

foreach($authorisationTemplateVariables as $variable){
    $variable=new $variable;
    $view->addGlobal($variable->getName(),$variable->getValue());
}
**/

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        var_dump("Hello");
        // ... 404 Not Found

        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        var_dump("Hi");
        // ... 405 Method Not Allowed

        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        [$controller, $method] = $handler;

        $response = (new $controller)->{$method}($vars);

        if ($response instanceof Template){
            echo $view->render($response->getPath(), $response->getParams());
            unset($_SESSION['error']);
        }

        if($response instanceof Redirect){
            header('Location:'.$response->getUrl());
        }
        break;
}