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
use Oacc\Repository\DroneRepository;
use Oacc\Repository\SquadronRepository;
use Oacc\Services\DroneUtilities;
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
     * @var DroneUtilities
     */
    private $droneUtilities;

    /**
     * SquadronController constructor.
     *
     * @param EntityManager $em
     * @param DroneUtilities $droneUtilities
     */
    public function __construct(EntityManager $em, DroneUtilities $droneUtilities)
    {
        $this->em = $em;
        $this->droneUtilities = $droneUtilities;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     *
     * @return Response
     */
    public function getAllAction(Request $request, Response $response, $args)
    {
        /** @var Response $response */
        $response = $response->withHeader('Content-type', 'application/json');
        $squadronRepository = $this->em->getRepository('Oacc\Entity\Squadron');
        $data = false;
        $message = '';
        $squadrons = $squadronRepository->findAll();
        if ($squadrons) {
            $data = $this->getAllData($squadrons);
            $message = "Squadrons found";
        }
        if ($data && $message) {
            $messages = ['message' => $message];

            return $this->setSuccessJson($response, $messages, $data);
        } else {
            $messages = [
                'message' => "Squadron not found",
            ];

            return $this->setErrorJson($response, $messages, 404);
        }
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
            'cash' => $squadron->getCash(),
            'drones' => $drones,
        ];
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     *
     * @return Response
     */
    public function getAction(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        /** @var Response $response */
        $response = $response->withHeader('Content-type', 'application/json');
        $squadronRepository = $this->em->getRepository('Oacc\Entity\Squadron');
        /** @var Squadron $squadrons */
        $squadrons = $squadronRepository->findOneBy(['id' => $id]);
        if ($squadrons) {
            $data = $this->getData($squadrons);
            $messages = ['message' => "Squadrons found"];

            return $this->setSuccessJson($response, $messages, $data);
        } else {
            $messages = [
                'message' => "Squadron not found",
            ];

            return $this->setErrorJson($response, $messages, 404);
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

    public function putDronesAction(Request $request, Response $response, $args)
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $id = $args['id'];
        /** @var Squadron $squadron */
        $squadron = $this->em->getRepository('Oacc\Entity\Squadron')->find($id);
        if (!$squadron) {
            $errors = ['message' => "Squadron not found"];

            return $this->setErrorJson($response, $errors, 404);
        }

        /** @var DroneRepository $droneRepository */
        $droneRepository = $this->em->getRepository('Oacc\Entity\Drone');

        $post = json_decode($request->getBody(), true);
        if (array_key_exists('drones', $post)) {
            foreach($post['drones'] as $postedDrone) {
                $drone = $droneRepository->find($postedDrone['id']);
                if (!$drone) {
                    $errors = ['message' => "One of the drones could not be found"];

                    return $this->setErrorJson($response, $errors, 404);
                }

                $thrusterPower = $this->droneUtilities->updateStat('thruster_power', $postedDrone, $drone->getThrusterPower());
                $turningSpeed = $this->droneUtilities->updateStat('turning_speed', $postedDrone, $drone->getTurningSpeed());

                $drone->setThrusterPower($thrusterPower);
                $drone->setTurningSpeed($turningSpeed);

                try {
                    $this->droneUtilities->spendCash($squadron);
                } catch(\Exception $e) {
                    $errors[] = ['message' => $e->getMessage()];

                    return $this->setErrorJson($response, $errors, 400);
                }

                $this->em->persist($drone);
            }
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
}