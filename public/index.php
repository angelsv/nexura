<?php

declare(strict_types=1);

// Procesos comunes para la carga inicial
require dirname(__DIR__) . '/bootstrap/bootstrap.php';

/**
 * Manejo de rutas
 */
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    // Home
    $r->addRoute('GET', '/', 'App\Controllers\ViewsController/showIndex');

    // Crear
    $r->addRoute('GET', '/employee/new', 'App\Controllers\EmployeeController/showForm');
    $r->addRoute('POST', '/employee/new', 'App\Controllers\EmployeeController/save');
    // Editar
    $r->addRoute('GET', '/employee/{id:\d+}', 'App\Controllers\EmployeeController/getById');
    $r->addRoute('POST', '/employee/{id:\d+}', 'App\Controllers\EmployeeController/save');

    // Listar
    $r->addRoute('GET', '/employee', 'App\Controllers\EmployeeController/getAll');
    // $r->addRoute('GET', '/users', 'App\Controllers\UsersController/getUsers');
    // $r->addRoute('GET', '/users/upload', 'App\Controllers\ViewsController/showUsersUpload');
    // $r->addRoute('POST', '/users/upload', 'App\Controllers\UsersController/uploadUsers');
    // // {id} must be a number (\d+)
    // $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
    // // The /{title} suffix is optional
    // $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo "// ... 404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo "// ... 405 Method Not Allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2] ?? array();
        list($class, $method) = explode("/", $handler, 2);
        call_user_func_array(array(new $class, $method), $vars);
        break;
    default:
        echo "// ... others";
        break;
}


exit;

// Timming
$ru = getrusage();
echo "This process used " . rutime($ru, $rustart, "utime") .
    " ms for its computations\n  <br /><br />";
echo "It spent " . rutime($ru, $rustart, "stime") .
    " ms in system calls\n  <br /><br />";
