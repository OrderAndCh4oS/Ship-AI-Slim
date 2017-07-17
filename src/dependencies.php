<?php
// DIC configuration

use Slim\Container;

$container = $app->getContainer();

$container['logger'] = function (Container $container) {
    $settings = $container->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Register component on container
$container['view'] = function (Container $container) {
    $settings = $container->get('settings')['view'];
    if ($settings['debug'] == true) {
        $viewOptions = [
            'cache' => false,
            'debug' => true,
        ];
    } else {
        $viewOptions = [
            'cache' => __DIR__.'/../cache'
        ];
    }

    $view = new \Slim\Views\Twig($settings["template_path"], $viewOptions);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$container['csrf'] = function ($c) {
    return new \Slim\Csrf\Guard;
};

$container['doctrine'] = function (Container $container) {

};