<?php
/**
 * Created by PhpStorm.
 * User: Sarco
 * Date: 17/08/2017
 * Time: 14:34
 */

namespace Oacc\Controller\API\V1;


use Slim\Http\Response;

class BaseAPIController
{
    /**
     * @param Response $response
     * @param $error_messages
     *
     * @return Response
     */
    protected function setErrorJson(Response $response, $error_messages)
    {
        $json = json_encode(
            [
                'status' => 'error',
                'errors' => $error_messages,
            ]
        );
        $response->getBody()->write($json);

        return $response->withStatus(400);
    }
}