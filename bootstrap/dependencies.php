<?php

$container = $app->getContainer();

$db = require_once __DIR__ . '/db.php';

$container['db'] = function () use ($db) {
    $capsule = new \Illuminate\Database\Capsule\Manager();
    $capsule->addConnection($db);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

$container['csrf'] = function () {
    return new \Slim\Csrf\Guard();
};

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(
        $container['settings']['view']['template_path'],
        $container['settings']['view']['twig']
    );

    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));
    $view->addExtension(new Twig_Extension_Debug());

    $view->getEnvironment()->addGlobal('flash', $container['flash']);

    return $view;
};
