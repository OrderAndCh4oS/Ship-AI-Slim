<?php
/**
 * Created by PhpStorm.
 * User: Sarco
 * Date: 15/08/2017
 * Time: 20:41
 */

namespace Oacc\Controller\API\V1;


use Doctrine\ORM\EntityManager;
use Oacc\Entity\Drone;
use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;

class DroneController
{

    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var Guard
     */
    private $csrf;

    /**
     * DroneController constructor.
     *
     * @param EntityManager $em
     * @param Guard $csrf
     */
    public function __construct(EntityManager $em, Guard $csrf)
    {
        $this->em   = $em;
        $this->csrf = $csrf;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     *
     * @return Response|static
     */
    public function getAction(Request $request, Response $response, $args)
    {
        $id       = $args['id'];
        $response = $response->withHeader('Content-type', 'application/json');

        /**
         * @var Drone $drone
         */
        $drone = $this->em->getRepository('Oacc\Entity\Drone')->find($id);
        if ($drone) {
            $json = json_encode([
                'status' => 'success',
                'data' => [
                    'id'             => (int) $drone->getId(),
                    'name'           => $drone->getName(),
                    'thruster_power' => (int) $drone->getThrusterPower(),
                    'turning_speed'  => (int) $drone->getTurningSpeed(),
                    'kills'          => (int) $drone->getKills()
                ]
            ]);
            $response->getBody()->write($json);
            return $response->withStatus(200);

        } else {
            $json = json_encode([
                'status' => 'error',
                'message' => 'Not Found'
            ]);
            $response->getBody()->write($json);
            return $response->withStatus(404);
        }
    }

    public function postAction(Request $request, Response $response, $args)
    {
        return $response->getBody()->write("posted, I guess? ");
    }

    public function putAction(Request $request, Response $response, $args)
    {
        return $response->getBody()->write("edited, I guess? ");
    }

    public function deleteAction(Request $request, Response $response, $args)
    {
        return $response->getBody()->write("deleted, I guess? ");
    }
}