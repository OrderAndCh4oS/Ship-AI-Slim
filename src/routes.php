<?php
// Routes

$app->get(
    '/',
    function ($request, $response, $args) {
        // $this->logger->info("Slim-Skeleton '/' route");
        return $this->view->render(
            $response,
            'index.twig'
        );
    }
)->setName('menu');

$app->get('/ship-ai',
    function ($request, $response, $args) {
        // $this->logger->info("Slim-Skeleton '/' route");
        return $this->view->render(
            $response,
            'ship-ai.twig'
        );
    }
)->setName('ship-ai');