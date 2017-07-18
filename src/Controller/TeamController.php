<?php
/**
 * Created by PhpStorm.
 * User: sarcoma
 * Date: 17/07/17
 * Time: 22:56
 */

namespace Oacc\Controller;


use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class TeamController
{
    private $view;
    private $csrf;

    public function __construct(Twig $view, Guard $csrf)
    {
        $this->view = $view;
        $this->csrf = $csrf;
    }

    public function manageSquadronAction(Request $request, Response $response, $args) {

        if ($request->isPost()) {
            // handle post
        }

        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        return $this->view->render(
            $response,
            'manage-squadron.twig',
            [
                'nameKey' => $nameKey,
                'valueKey' => $valueKey,
                'name' => $name,
                'value' => $value,
            ]
        );
    }
}