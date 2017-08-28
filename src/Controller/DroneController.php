<?php
/**
 * Created by PhpStorm.
 * User: sarcoma
 * Date: 17/07/17
 * Time: 22:56
 */

namespace Oacc\Controller;


use Doctrine\ORM\EntityManager;
use Oacc\Entity\Drone;
use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class DroneController
{
    /**
     * @var Twig
     */
    private $view;

    /**
     * DroneController constructor.
     * @param Twig $view
     */
    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function manageDronesAction(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'manage-drones.twig');
    }
}

