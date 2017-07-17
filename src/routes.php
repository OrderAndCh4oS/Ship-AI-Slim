<?php
// Routes

use Slim\Http\Request;
use Slim\Http\Response;

$app->get(
    '/',
    function (Request $request, Response $response, $args) {
        return $this->view->render(
            $response,
            'index.twig'
        );
    }
)->setName('menu');

$app->map(
    ['post', 'get'],
    '/manage-squadron',
    function (Request $request, Response $response, $args) {

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
)->setName('manage-squadron');

$app->get(
    '/ship-ai',
    function (Request $request, Response $response, $args) {
        return $this->view->render(
            $response,
            'ship-ai.twig'
        );
    }
)->setName('ship-ai');