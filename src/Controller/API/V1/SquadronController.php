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
use Oacc\Entity\Squadron;
use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;

class SquadronController extends BaseAPIController
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
     * SquadronController constructor.
     *
     * @param EntityManager $em
     * @param Guard $csrf
     */
    public function __construct(EntityManager $em, Guard $csrf)
    {
        $this->em = $em;
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
        $id = $args['id'];
        $response = $response->withHeader('Content-type', 'application/json');

        /**
         * @var Squadron $squadron
         */
        $squadron = $this->em->getRepository('Oacc\Entity\Squadron')->find($id);
        if ($squadron) {

            $drones = [];

            /**
             * @var Drone $drone
             */
            foreach ($squadron->getDrones() as $drone) {
                $drones[] = [
                    'id' => (int)$drone->getId(),
                    'name' => $drone->getName(),
                    'thruster_power' => (int)$drone->getThrusterPower(),
                    'turning_speed' => (int)$drone->getTurningSpeed(),
                    'kills' => (int)$drone->getKills(),
                ];
            }

            $json = json_encode(
                [
                    'status' => 'success',
                    'data' => [
                        'id' => (int)$squadron->getId(),
                        'name' => $squadron->getName(),
                        'cash' => $squadron->getName(),
                        'drones' => $drones,
                    ],
                ]
            );
            $response->getBody()->write($json);

            return $response->withStatus(200);

        } else {
            $json = json_encode(
                [
                    'status' => 'error',
                    'errors' => [
                        'message' => "Squadron not found"
                    ]
                ]
            );
            $response->getBody()->write($json);

            return $response->withStatus(404);
        }
    }

    public function postAction(Request $request, Response $response, $args)
    {
        $post = json_decode($request->getBody(), true);

        $errors = [];

        if ( ! array_key_exists('name', $post)) {
            $errors[] = ['message' => 'JSON missing the name key'];
        }

        if (empty($post['name'])) {
            $errors[] = ['message' => 'Name must not be empty'];
        }

        if ( ! empty($errors)) {
            return $this->setErrorJson($response, $errors);
        }

        $squadron = new Squadron();

        $squadron->setName($post['name']);
        for ($i = 1; $i <= 10; $i++) {
            $drone = new Drone;
            $drone->setName('Drone ' . $i);
            $drone->setSquadron($squadron);
            $this->em->persist($drone);
        }
        $this->em->persist($squadron);
        $this->em->flush();

        $json = json_encode(
            [
                'status' => 'success',
                'data'   => [
                    'message' => 'Squadron has been created',
                ],
            ]
        );

        $response->getBody()->write($json);

        return $response->withStatus(200);
    }

    public function putAction(Request $request, Response $response, $args)
    {
        return $response->getBody()->write("edited, I guess?");
    }

    public function deleteAction(Request $request, Response $response, $args)
    {
        return $response->getBody()->write("deleted, I guess?");
    }
}