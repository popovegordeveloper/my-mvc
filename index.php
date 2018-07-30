<?php

require_once  './vendor/autoload.php';
require __DIR__ . '/core/bootstrap.php';

use Routing\Router;
use Routing\MatchedRoute;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Database\Capsule\Manager as Capsule;

try {
    //Load app config
    $config = Yaml::parseFile('./config.yaml');

    //Database connection
    $capsule = new Capsule;
    $capsule->addConnection($config['database']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    //Routing
    $router = new Router(GET_HTTP_HOST());
    $routes = Yaml::parseFile('./routes/web.yaml');
    foreach ($routes as $route => $parameters){
        $router->add($route, $parameters['pattern'], $parameters['controller'] . ':' . $parameters['action']);
    }
    $route = $router->match(GET_METHOD(), GET_PATH_INFO());

//    if (null == $route) {
//        $route = new MatchedRoute('Controller:error404Action');
//    }

    list($class, $action) = explode(':', $route->getController(), 2);
    call_user_func_array(array(new $class($router), $action), $route->getParameters());

} catch (Exception $e) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    echo $e->getMessage();
    echo $e->getTraceAsString();
    exit;
}