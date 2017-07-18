<?php
// DIC configuration

use Slim\Container;
use Slim\Csrf\Guard;

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

$container['csrf'] = function () {
    return new Guard;
};

$container['Oacc\Controller\MenuController'] = function (Container $container) {
    return new Oacc\Controller\MenuController($container->get('view'));
};

$container['Oacc\Controller\ShipController'] = function (Container $container) {
    return new Oacc\Controller\ShipController($container->get('view'));
};

$container['Oacc\Controller\TeamController'] = function (Container $container) {
    return new Oacc\Controller\TeamController($container->get('view'), $container->get('csrf'));
};

$container['Oacc\Controller\GameController'] = function (Container $container) {
    return new Oacc\Controller\GameController($container->get('view'), $container->get('csrf'));
};

$container['em'] = function (Container $container) {
    $settings = $container->get('settings');
    $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
        $settings['doctrine']['meta']['entity_path'],
        $settings['doctrine']['meta']['auto_generate_proxies'],
        $settings['doctrine']['meta']['proxy_dir'],
        $settings['doctrine']['meta']['cache'],
        false
    );
    return \Doctrine\ORM\EntityManager::create($settings['doctrine']['connection'], $config);
};