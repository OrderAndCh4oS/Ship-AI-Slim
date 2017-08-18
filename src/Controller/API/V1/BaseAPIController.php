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
     * @param int $status_code
     * @return Response
     */
    protected function setErrorJson(Response $response, $error_messages, $status_code = 400)
    {
        $json = json_encode(
            [
                'status' => 'error',
                'errors' => $error_messages,
            ]
        );
        $response->getBody()->write($json);

        return $response->withStatus($status_code);
    }

    /**
     * @param Response $response
     * @param $messages
     * @param array $data
     * @param int $status_code
     * @return Response
     */
    protected function setSuccessJson(Response $response, $messages, $data = [], $status_code = 200)
    {
        $json = json_encode(
            [
                'status' => 'success',
                'data' => $data,
                'messages' => $messages,
            ]
        );
        $response->getBody()->write($json);

        return $response->withStatus($status_code);
    }
}