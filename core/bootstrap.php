<?php

ini_set('display_errors', 'on');
error_reporting(E_ALL);

spl_autoload_register(function ($class) {
    $path = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (is_file($path)) {
        require $path;
        return;
    }
    throw new \LogicException(sprintf('Class "%s" not found in "%s"', $class, $path));
});

require __DIR__ . '/function.php';

$controllers = get_files('/controllers');
foreach ($controllers as $controller) {
    require $_SERVER['DOCUMENT_ROOT'] . "/controllers/$controller";
}
