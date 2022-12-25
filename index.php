<?php

use App\Repositories\DatabaseApiRepository;
use App\Repositories\DatabaseRepository;
use App\Repositories\RequestApiRepository;
use App\Repositories\RequestRepository;
use App\Repositories\SymbolApiRepository;
use App\Repositories\SymbolRepository;
use App\Services\RequestService;
use App\Services\SymbolService;
use App\TemplateVariables\AuthorisationTemplateVariables;
use App\TemplateVariables\BilanceVariables;
use App\TemplateVariables\Errors;
use App\TemplateVariables\ErrorsSell;
use App\TemplateVariables\ErrorsSend;
use App\TemplateVariables\TransactionsVariables;
use App\TemplateVariables\WalletVariables;
use App\Views\Redirect;
use Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Views\Template;
use function DI\create;

require 'vendor/autoload.php';

session_start();

$dotenv = Dotenv::createMutable(__DIR__);
$dotenv->load();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $route) {
    $route->addRoute('GET', '/', ['App\Controllers\RequestController', 'index']);
    $route->addRoute('GET', '/search', ['App\Controllers\RequestController', 'index']);
    $route->addRoute('GET', '/registration', ['App\Controllers\UserController', 'showForm']);
    $route->addRoute('POST', '/registration', ['App\Controllers\UserController', 'useNewUser']);
    $route->addRoute('POST', '/login', ['App\Controllers\UserController', 'loginUser']);
    $route->addRoute('GET', '/logout', ['App\Controllers\UserController', 'logoutUser']);
    $route->addRoute('GET', '/convert', ['App\Controllers\RequestController', 'index']);
    $route->addRoute('GET', '/change', ['App\Controllers\UserController', 'change']);
    $route->addRoute('POST', '/changeUserEmail', ['App\Controllers\UserController', 'changeUser']);
    $route->addRoute('POST', '/changePassword', ['App\Controllers\UserController', 'changePassword']);
    $route->addRoute('GET', '/symbol/{symbol}', ['App\Controllers\SymbolController', 'showSymbol']);
    $route->addRoute('POST', '/symbol', ['App\Controllers\SymbolController', 'buyCurrency']);
    $route->addRoute('POST', '/slot', ['App\Controllers\SymbolController', 'slot']);
    $route->addRoute('GET', '/wallet', ['App\Controllers\WalletController', 'showWallet']);
    $route->addRoute('GET', '/transactions', ['App\Controllers\TransactionsController', 'showTransactions']);
    $route->addRoute('GET', '/send', ['App\Controllers\SendController', 'showSend']);
    $route->addRoute('POST', '/send', ['App\Controllers\SendController', 'send']);

});

$loader = new FilesystemLoader('app/Views/Templates');
$view = new Environment($loader);

$container = new DI\Container();
$container->set(RequestRepository::class,
    create(RequestApiRepository::class));
$container->set(DatabaseRepository::class,
    create(DatabaseApiRepository::class));
$container->set(SymbolRepository::class,
    create(SymbolApiRepository::class));

$authorisationTemplateVariables = [
    AuthorisationTemplateVariables::class,
    Errors::class,
    ErrorsSell::class,
    ErrorsSend::class
];

$authorisationTemplateVariablesWithService = [
    TransactionsVariables::class,
    BilanceVariables::class,
];

foreach ($authorisationTemplateVariablesWithService as $variable) {
    $variable = new $variable(new SymbolService(new SymbolApiRepository(), new RequestApiRepository()));
    $view->addGlobal($variable->getName(), $variable->getValue());
}
$variable = new WalletVariables(
    new SymbolService(
        new SymbolApiRepository(),
        new RequestApiRepository()),
    new RequestService(
        new RequestApiRepository()
    )
);
$view->addGlobal($variable->getName(), $variable->getValue());

foreach ($authorisationTemplateVariables as $variable) {
    $variable = new $variable;
    $view->addGlobal($variable->getName(), $variable->getValue());
}


$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found

        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed

        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        [$controller, $method] = $handler;

        $response = $container->get($controller)->{$method}($vars);

        if ($response instanceof Template) {
            echo $view->render($response->getPath(), $response->getParams());
            unset($_SESSION['error']);
            unset($_SESSION['sellError']);

        }

        if ($response instanceof Redirect) {
            header('Location:' . $response->getUrl());
        }
        break;
}