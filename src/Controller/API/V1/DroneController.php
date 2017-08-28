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

class DroneController extends BaseAPIController
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
     * @param $drone
     * @return array
     */
    private function getData(Drone $drone)
    {
        return [
            'id' => (int)$drone->getId(),
            'name' => $drone->getName(),
            'thruster_power' => (int)$drone->getThrusterPower(),
            'turning_speed' => (int)$drone->getTurningSpeed(),
            'kills' => (int)$drone->getKills(),
        ];
    }

    /**
     * DroneController constructor.
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

        $id = $args['id'];

        /**
         * @var Drone $drone
         */
        $drone = $this->em->getRepository('Oacc\Entity\Drone')->find($id);
        if ($drone) {
            $data = $this->getData($drone);
            $messages = ['message' => 'Drone found'];

            return $this->setSuccessJson($response, $messages, $data);
        } else {
            $errors = ['message' => "Drone not found"];

            return $this->setErrorJson($response, $errors, 404);
        }
    }

    public function postAction(Request $request, Response $response, $args)
    {
        /** @var Response $response */
        $response = $response->withHeader('Content-type', 'application/json');

        $post = json_decode($request->getBody(), true);
        $errors = [];

        if (!array_key_exists('squadron_id', $post)) {
            $errors[] = [
                'field' => 'squadron',
                'message' => 'JSON missing the squadron_id key',
            ];
        }

        if (!array_key_exists('name', $post)) {
            $errors[] = [
                'field' => 'name',
                'message' => 'You must enter a name',
            ];
        }

        if (empty($post['name'])) {
            $errors[] = ['message' => 'Name must not be empty'];

        }

        if (!empty($errors)) {
            return $this->setErrorJson($response, $errors);
        }

        $squadronRepository = $this->em->getRepository('Oacc\Entity\Squadron');
        $squadron = $squadronRepository->find($post['squadron_id']);

        if (!$squadron) {
            $errors[] = ['message' => 'Squadron was not found'];

            return $this->setErrorJson($response, $errors);
        }

        $drone = new Drone();

        $drone->setName($post['name']);
        $drone->setSquadron($squadron);
        $this->em->persist($drone);
        $this->em->flush();

        $messages = ['message' => 'Drone has been created'];
        $data = $this->getData($drone);

        return $this->setSuccessJson($response, $messages, $data);
    }

    public function putAction(Request $request, Response $response, $args)
    {
        /** @var Response $response */
        $response = $response->withHeader('Content-type', 'application/json');
        $id = $args['id'];

        /** @var Drone $drone */
        $drone = $this->em->getRepository('Oacc\Entity\Drone')->find($id);

        $errors = [];

        if (!$drone) {
            $errors[] = ['message' => "Drone not found"];
            return $this->setErrorJson($response, $errors, 404);
        }

        $post = json_decode($request->getBody(), true);

        if (array_key_exists('name', $post)) {
            $drone->setName($post['name']);
        }

        $statChangeCost = 0;

        if (array_key_exists('thruster_power', $post)) {
            $difference = $post['thruster_power'] - $drone->getThrusterPower();
            $drone->setThrusterPower($post['thruster_power']);
            if ($difference > 0) {
                $statChangeCost += $difference;
            }
        }

        if (array_key_exists('turning_speed', $post)) {
            $difference = $post['turning_speed'] - $drone->getTurningSpeed();
            $drone->setTurningSpeed($post['turning_speed']);
            if ($difference > 0) {
                $statChangeCost += $difference;
            }
        }

        if (array_key_exists('kills', $post)) {
            $kills = $drone->getKills() + $post['kills'];
            $drone->setKills($kills);
        }

        if ($statChangeCost <= $drone->getSquadron()->getCash()) {
            $squadron = $drone->getSquadron();
            $cash = $squadron->getCash() - $statChangeCost;
            $squadron->setCash($cash);
            $this->em->persist($squadron);
        } else {
            $errors[] = ['message' => 'Not enough cash for upgrade'];

            return $this->setErrorJson($response, $errors, 400);
        }

        $this->em->persist($drone);
        $this->em->flush();

        $data = $this->getData($drone);

        $messages = ['message' => 'Drone has been updated'];

        return $this->setSuccessJson($response, $messages, $data);
    }

    public function deleteAction(Request $request, Response $response, $args)
    {
        /** @var Response $response */
        $response = $response->withHeader('Content-type', 'application/json');
        $id = $args['id'];
        $drone = $this->em->getRepository('Oacc\Entity\Drone')->find($id);
        if (!$drone) {
            $errors = ['message' => "Drone not found"];

            return $this->setErrorJson($response, $errors, 404);
        }
        $this->em->remove($drone);
        $this->em->flush();

        $messages = ['message' => 'Drone has been deleted'];

        return $this->setSuccessJson($response, $messages);
    }
}