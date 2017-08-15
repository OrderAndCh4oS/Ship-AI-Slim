<?php
// Routes

use Oacc\Controller\MenuController;
use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', 'Oacc\Controller\MenuController:indexAction')->setName('menu');

$app->map(['post', 'get'],'/manage-squadron','Oacc\Controller\SquadronController:manageSquadronAction')->setName('manage-squadron');
$app->map(['post', 'get'], '/manage-drones/{id}','Oacc\Controller\DroneController:manageDronesAction')->setName('manage-drones');

$app->get('/select-squadrons', 'Oacc\Controller\MenuController:selectSquadronsAction')->setName('select-squadron');
$app->post('/ship-ai', 'Oacc\Controller\GameController:gameAction')->setName('ship-ai');

$app->group('/api/v1', function() {
    $this->get('/drone/{id}', 'Oacc\Controller\API\V1\DroneController:getAction');
    $this->post('/drone/{id}', 'Oacc\Controller\API\V1\DroneController:postAction');
});