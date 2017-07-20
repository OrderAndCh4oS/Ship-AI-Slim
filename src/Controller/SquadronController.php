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
     * @var Guard
     */
    private $csrf;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * SquadronController constructor.
     * @param Twig $view
     * @param Guard $csrf
     * @param EntityManager $em
     */
    public function __construct(Twig $view, Guard $csrf, EntityManager $em)
    {
        $this->view = $view;
        $this->csrf = $csrf;
        $this->em = $em;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function manageSquadronAction(Request $request, Response $response, $args) {
        if ($request->isPost()) {
            $squadron = new Squadron;
            $squadron->setName($request->getParam('name'));
            for ($i = 1; $i <= 10; $i++) {
                $drone = new Drone;
                $drone->setName('Drone '.$i);
                $drone->setSquadron($squadron);
                $this->em->persist($drone);
            }
            $this->em->persist($squadron);
            $this->em->flush();
            return $this->view->render(
                $response,
                'manage-drones.twig',
                [
                    'squadron' => $squadron
                ]
            );
        }

        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        $squadrons = $this->em->getRepository('Oacc\Entity\Squadron')->findAll();

        return $this->view->render(
            $response,
            'manage-squadrons.twig',
            [
                'nameKey' => $nameKey,
                'valueKey' => $valueKey,
                'name' => $name,
                'value' => $value,
                'squadrons' => $squadrons
            ]
        );
    }
}