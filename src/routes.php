<?php
// Routes

use Oacc\Controller\MenuController;
use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', 'Oacc\Controller\MenuController:indexAction')->setName('menu');

$app->map(['post', 'get'],'/manage-squadron','Oacc\Controller\SquadronController:manageSquadronAction')->setName('manage-squadron');
$app->get('/manage-drones/{id}','Oacc\Controller\DroneController:manageDronesAction')->setName('manage-drones');

$app->get('/ship-ai', 'Oacc\Controller\GameController:gameAction')->setName('ship-ai');