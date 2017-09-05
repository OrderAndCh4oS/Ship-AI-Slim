<?php
// Routes

use Oacc\Controller\MenuController;
use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', 'Oacc\Controller\MenuController:indexAction')->setName('menu');

$app->map(
    ['post', 'get'],
    '/squadrons/manage',
    'Oacc\Controller\SquadronController:manageSquadronAction'
)->setName('manage-squadron');

$app->map(
    ['post', 'get'],
    '/squadrons/{id}/manage',
    'Oacc\Controller\SquadronController:manageDronesAction'
)->setName('manage-drones');

$app->get('/drone-ai', 'Oacc\Controller\GameController:gameAction')->setName('drone-ai');

$app->group(
    '/api/v1',
    function () {
        $this->get('/drones/{id}', 'Oacc\Controller\API\V1\DroneController:getAction');
        $this->post('/drones', 'Oacc\Controller\API\V1\DroneController:postAction');
        $this->put('/drones/{id}', 'Oacc\Controller\API\V1\DroneController:putAction');
        $this->delete('/drones/{id}', 'Oacc\Controller\API\V1\DroneController:deleteAction');
        $this->get('/squadrons', 'Oacc\Controller\API\V1\SquadronController:getAllAction');
        $this->get('/squadrons/{id}', 'Oacc\Controller\API\V1\SquadronController:getAction');
        $this->put('/squadrons/{id}/drones', 'Oacc\Controller\API\V1\SquadronController:putDronesAction');
        $this->post('/squadrons', 'Oacc\Controller\API\V1\SquadronController:postAction');
        $this->put('/squadrons/{id}', 'Oacc\Controller\API\V1\SquadronController:putAction');
        $this->delete('/squadrons/{id}', 'Oacc\Controller\API\V1\SquadronController:deleteAction');
    }
);