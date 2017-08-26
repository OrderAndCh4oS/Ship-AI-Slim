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
        /** @var Response $response */
        $response = $response->withHeader('Content-type', 'application/json');
        $squadronRepository = $this->em->getRepository('Oacc\Entity\Squadron');
        $data = false;
        $message = '';
        // ToDo: Clean up this utter fuckfest
        if (isset($args['id'])) {
            /** @var Squadron $squadron */
            $squadron = $squadronRepository->find($args['id']);
            if ($squadron) {
                $data = $this->getData($squadron);
                $message = "Squadron found";
            }
        } else {
            $squadrons = $squadronRepository->findAll();
            if ($squadrons) {
                $data = $this->getAllData($squadrons);
                $message = "Squadrons found";
            }
        }

        if ($data && $message) {

            $messages = ['message' => $message];

            return $this->setSuccessJson($response, $messages, $data);

        } else {
            $json = json_encode(
                [
                    'status' => 'error',
                    'errors' => [
                        'message' => "Squadron not found",
                    ],
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

        if (!array_key_exists('name', $post)) {
            $errors[] = [
                'field' => 'name',
                'message' => 'Must provide name',
            ];
        }

        if (empty($post['name'])) {
            $errors[] = [
                'field' => 'name',
                'message' => 'Name must not be empty',
            ];
        }

        if (!empty($errors)) {
            return $this->setErrorJson($response, $errors);
        }

        $squadron = new Squadron();

        $squadron->setName($post['name']);
        for ($i = 1; $i <= 10; $i++) {
            $drone = new Drone;
            $drone->setName('Drone '.$i);
            $drone->setSquadron($squadron);
            $this->em->persist($drone);
        }
        $this->em->persist($squadron);
        $this->em->flush();

        $data = $this->getData($squadron);

        $messages = ['message' => 'Squadron has been created'];

        return $this->setSuccessJson($response, $messages, $data);
    }

    public function putAction(Request $request, Response $response, $args)
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $id = $args['id'];

        /**
         * @var Squadron $squadron
         */
        $squadron = $this->em->getRepository('Oacc\Entity\Squadron')->find($id);

        if (!$squadron) {
            $errors = ['message' => "Squadron not found"];

            return $this->setErrorJson($response, $errors, 404);
        }

        $post = json_decode($request->getBody(), true);

        if (array_key_exists('name', $post)) {
            $squadron->setName($post['name']);
        }

        if (array_key_exists('cash', $post)) {
            $cash = $squadron->getCash() + $post['cash'];
            $squadron->setCash($cash);
        }

        $this->em->persist($squadron);
        $this->em->flush();

        $data = $this->getData($squadron);
        $messages = ['message' => 'Squadron has been updated'];

        return $this->setSuccessJson($response, $messages, $data);
    }

    public function deleteAction(Request $request, Response $response, $args)
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $id = $args['id'];
        $squadron = $this->em->getRepository('Oacc\Entity\Squadron')->find($id);
        if (!$squadron) {
            $errors = ['message' => "Squadron not found"];

            return $this->setErrorJson($response, $errors, 404);
        }

        $this->em->remove($squadron);
        $this->em->flush();

        $messages = ['message' => 'Squadron has been deleted'];

        return $this->setSuccessJson($response, $messages);
    }

    private function getAllData($squadrons)
    {
        $data = [];
        foreach ($squadrons as $squadron) {
            $data[] = $this->getData($squadron);
        }

        return $data;
    }

    /**
     * @param $squadron
     * @return array
     */
    private function getData(Squadron $squadron)
    {
        $drones = [];

        /** @var Drone $drone */
        foreach ($squadron->getDrones() as $drone) {
            $drones[] = [
                'id' => (int)$drone->getId(),
                'name' => $drone->getName(),
                'thruster_power' => (int)$drone->getThrusterPower(),
                'turning_speed' => (int)$drone->getTurningSpeed(),
                'kills' => (int)$drone->getKills(),
            ];
        }

        return [
            'id' => (int)$squadron->getId(),
            'name' => $squadron->getName(),
            'cash' => $squadron->getName(),
            'drones' => $drones,
        ];
    }
}