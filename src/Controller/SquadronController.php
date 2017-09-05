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
use Oacc\Entity\Squadron;
use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class SquadronController
{
    /**
     * @var Twig
     */
    private $view;

    /**
     * SquadronController constructor.
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
    public function manageSquadronAction(Request $request, Response $response, $args) {
        return $this->view->render($response, 'manage-squadrons.twig');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function manageDronesAction(Request $request, Response $response, $args)
    {
        $squadron_id = $args['id'];

        return $this->view->render(
            $response,
            'manage-drones.twig',
            [
                'squadron_id' => $squadron_id,
            ]
        );
    }
}