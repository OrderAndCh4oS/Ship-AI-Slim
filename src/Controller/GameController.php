<?php
/**
 * Created by PhpStorm.
 * User: HP-DEV3
 * Date: 18/07/2017
 * Time: 13:41
 */

namespace Oacc\Controller;

use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;


class GameController
{
    private $view;
    private $csrf;

    public function __construct(Twig $view, Guard $csrf)
    {
        $this->view = $view;
        $this->csrf = $csrf;
    }

    public function gameAction(Request $request, Response $response, $args) {
        return $this->view->render(
            $response,
            'ship-ai.twig'
        );
    }

}