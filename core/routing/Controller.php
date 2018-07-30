<?php

namespace Routing;

use Twig_Environment;
use Twig_Loader_Filesystem;

class Controller
{
    protected $router;
    protected $twig;
    protected $path_to_views;

    public function __construct(Router $router)
    {
        $this->path_to_views = 'views';
        $this->router = $router;
        $loader = new Twig_Loader_Filesystem([$this->path_to_views, $this->path_to_views . '/pages', $this->path_to_views . '/layouts']);
        $this->twig = new Twig_Environment($loader, array(
            'cache'       =>  'core/compilation_cache',
            'auto_reload' => true
        ));
    }

    protected function render($template, array $vars = array())
    {
        $templatePath = $template . '.php';
        echo $this->twig->render($templatePath, $vars);
    }

    public function error404Action()
    {
        header("HTTP/1.0 404 Not Found");
        self::render('error404');
    }
}