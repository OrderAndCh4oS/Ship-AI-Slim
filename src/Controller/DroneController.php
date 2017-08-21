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
     * @var Guard
     */
    private $csrf;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * DroneController constructor.
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
        $message = "";
        if ($request->isPost()) {
            $drone_id = $request->getParam('id');
            $drone_thruster_power = $request->getParam('thruster_power');
            $drone_turning_speed = $request->getParam('turning_speed');
            /**
             * @var Drone $drone
             */
            $drone = $this->em->getRepository('Oacc\Entity\Drone')->find($drone_id);
            $drone->setThrusterPower($drone_thruster_power);
            $drone->setTurningSpeed($drone_turning_speed);
            $this->em->persist($drone);
            $this->em->flush();
            $message = "Saved";
        }

        $squadron = $this->em->getRepository('Oacc\Entity\Squadron')->findOneBy(['id' => $args['id']]);
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
                'message' => $message
            ]
        );
    }
}

