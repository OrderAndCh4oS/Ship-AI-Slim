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
    $view->addExtension(new Twig_Extension_Debug());

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$container['csrf'] = function () {
    return new Guard;
};

$container['Oacc\Controller\MenuController'] = function (Container $container) {
    return new Oacc\Controller\MenuController($container->get('view'), $container->get('csrf'), $container->get('em'));
};

$container['Oacc\Controller\DroneController'] = function (Container $container) {
    return new Oacc\Controller\DroneController($container->get('view'), $container->get('csrf'), $container->get('em'));
};

$container['Oacc\Controller\SquadronController'] = function (Container $container) {
    return new Oacc\Controller\SquadronController($container->get('view'), $container->get('csrf'), $container->get('em'));
};

$container['Oacc\Controller\GameController'] = function (Container $container) {
    return new Oacc\Controller\GameController($container->get('view'), $container->get('csrf'), $container->get('em'));
};

$container['Oacc\Controller\API\V1\DroneController'] = function (Container $container) {
    return new Oacc\Controller\API\V1\DroneController($container->get('em'), $container->get('csrf'));
};

$container['Oacc\Controller\API\V1\SquadronController'] = function (Container $container) {
    return new Oacc\Controller\API\V1\SquadronController($container->get('em'), $container->get('csrf'));
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