<?php
/**
 * Created by PhpStorm.
 * User: sarcoma
 * Date: 17/08/17
 * Time: 12:22
 */

namespace Tests\Controller\API\V1;

use GuzzleHttp\Psr7\Response;
use TestUtilities\BaseAPITest;

class SquadronTest extends BaseAPITest
{
    public function testPOST()
    {
        $response = $this->postSquadron();
        $json = json_decode($response->getBody());
        $this->successStatusAsserts($response, $json);
        $this->squadronDataAsserts($json);
    }

    public function testPOSTInvalidData()
    {
        $data = [
            'name' => '',
        ];

        /** @var Response $response */
        $response = $this->client->post(
            '/api/v1/squadrons',
            [
                'body' => json_encode($data),
                'http_errors' => false,
            ]
        );

        $json = json_decode($response->getBody());
        $this->errorStatusAsserts($response, $json);

    }

    public function testGET()
    {
        /** @var Response $response */
        $response = $this->client->get('/api/v1/squadrons/1');
        $json = json_decode($response->getBody());
        $this->successStatusAsserts($response, $json);

    }

    public function testGETNotFound()
    {
        /** @var Response $response */
        $response = $this->client->get('/api/v1/squadrons/0', ['http_errors' => false]);
        $json = json_decode($response->getBody());
        $this->errorStatusAsserts($response, $json, 404);
    }

    public function testPUT()
    {
        $data = [
            'name' => 'Squadron PUT Test',
        ];

        /** @var Response $response */
        $response = $this->client->put(
            '/api/v1/squadrons/1',
            [
                'body' => json_encode($data),
            ]
        );

        $json = json_decode($response->getBody());
        $this->successStatusAsserts($response, $json);

        $this->squadronDataAsserts($json);
    }

    public function testDELETE()
    {
        $response = $this->postSquadron();
        $json = json_decode($response->getBody());

        /** @var Response $response */
        $response = $this->client->delete('/api/v1/squadrons/'.$json->data->id);

        $json = json_decode($response->getBody());
        $this->successStatusAsserts($response, $json);
    }

    public function testDELETENotFound()
    {

        /** @var Response $response */
        $response = $this->client->delete('/api/v1/squadrons/0', ['http_errors' => false]);

        $json = json_decode($response->getBody());
        $this->errorStatusAsserts($response, $json, 404);
    }

    /**
     * @param $json
     */
    private function squadronDataAsserts($json)
    {
        $this->assertObjectHasAttribute('data', $json);
        $this->assertObjectHasAttribute('id', $json->data);
        $this->assertObjectHasAttribute('name', $json->data);
        $this->assertObjectHasAttribute('cash', $json->data);
        $this->assertObjectHasAttribute('drones', $json->data);
    }

    /**
     * @return Response
     */
    private function postSquadron()
    {
        $data = [
            'name' => 'Squadron Test',
        ];

        /** @var Response $response */
        $response = $this->client->post(
            '/api/v1/squadrons',
            [
                'body' => json_encode($data),
            ]
        );

        return $response;
    }
}
