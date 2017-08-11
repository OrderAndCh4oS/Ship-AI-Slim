<?php
/**
 * Created by PhpStorm.
 * User: sarcoma
 * Date: 17/07/17
 * Time: 22:56
 */

namespace Oacc\Controller;


use Doctrine\ORM\EntityManager;
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
     * @var Guard
     */
    private $csrf;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * ShipController constructor.
     * @param Twig $view
     * @param Guard $csrf
     * @param EntityManager $em
     */
    public function __construct(Twig $view, Guard $csrf, EntityManager $em)
    {
        $this->view = $view;
        $this->em = $em;
        $this->csrf = $csrf;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function manageDronesAction(Request $request, Response $response, $args)
    {

        if ($request->isPost()) {
            $body = $response->getBody();
            $body->write('Hello');
            return $response;
        }

        $squadron = $this->em->getRepository('Oacc\Entity\Squadron')->findBy(['id' => $args['id']]);
        $drones = $this->em->getRepository('Oacc\Entity\Drone')->findBy(['squadron' => $squadron]);

        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        return $this->view->render(
            $response,
            'manage-drones.twig',
            [
                'drones' => $drones,
                'nameKey' => $nameKey,
                'valueKey' => $valueKey,
                'name' => $name,
                'value' => $value,
            ]
        );
    }
}

