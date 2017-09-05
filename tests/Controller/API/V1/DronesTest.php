<?php
/**
 * Created by PhpStorm.
 * User: sarcoma
 * Date: 16/08/17
 * Time: 10:11
 */

namespace Tests\Controller\API\V1;

use GuzzleHttp\Psr7\Response;
use TestUtilities\BaseAPITest;

class DronesTest extends BaseAPITest
{
    private $drone_id = 2;

    public function testPOST()
    {
        $response = $this->postDrone();
        $json = json_decode($response->getBody());
        $this->successStatusAsserts($response, $json);
        $this->droneDataAsserts($json);
    }

    public function testPOSTInvalidData()
    {
        $data = [
            'name' => '',
            'squadron_id' => 0,
        ];

        /** @var Response $response */
        $response = $this->client->post(
            '/api/v1/drones',
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
        $response = $this->client->get('/api/v1/drones/'.$this->drone_id);
        $json = json_decode($response->getBody());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertObjectHasAttribute('status', $json);
        $this->assertEquals('success', $json->status);
        $this->droneDataAsserts($json);
    }

    public function testGETNotFound()
    {
        /** @var Response $response */
        $response = $this->client->get('/api/v1/drones/0', ['http_errors' => false]);
        $json = json_decode($response->getBody());
        $this->errorStatusAsserts($response, $json, 404);
    }

    public function testPUT()
    {
        $data = [
            'name' => 'Drone PUT Test',
            'thruster_power' => 24,
            'turning_speed' => 24,
            'kills' => 1,
        ];

        /** @var Response $response */
        $response = $this->client->put(
            '/api/v1/drones/'.$this->drone_id,
            [
                'body' => json_encode($data),
            ]
        );

        $json = json_decode($response->getBody());
        $this->successStatusAsserts($response, $json);
        $this->droneDataAsserts($json);
    }

    public function testPUTNotFound()
    {
        $data = [
            'name' => 'Drone PUT Test',
            'thruster_power' => 19,
            'turning_speed' => 19,
            'kills' => 1,
        ];

        /** @var Response $response */
        $response = $this->client->put(
            '/api/v1/drones/0',
            [
                'body' => json_encode($data),
                'http_errors' => false,
            ]
        );

        $json = json_decode($response->getBody());
        $this->errorStatusAsserts($response, $json, 404);

    }

    public function testPUTNotEnoughCash()
    {
        $data = [
            'name' => 'Drone PUT Test',
            'thruster_power' => 200,
            'turning_speed' => 200,
            'kills' => 1,
        ];

        /** @var Response $response */
        $response = $this->client->put(
            '/api/v1/drones/'.$this->drone_id,
            [
                'body' => json_encode($data),
                'http_errors' => false,
            ]
        );

        $json = json_decode($response->getBody());
        $this->errorStatusAsserts($response, $json, 400);

    }

    public function testDELETE()
    {
        $response = $this->postDrone();
        $json = json_decode($response->getBody());

        /** @var Response $response */
        $response = $this->client->delete('/api/v1/drones/'.$json->data->id);

        $json = json_decode($response->getBody());
        $this->successStatusAsserts($response, $json);
    }

    public function testDELETENotFound()
    {

        /** @var Response $response */
        $response = $this->client->delete('/api/v1/drones/0', ['http_errors' => false]);

        $json = json_decode($response->getBody());
        $this->errorStatusAsserts($response, $json, 404);
    }

    /**
     * @param $json
     */
    private function droneDataAsserts($json)
    {
        $this->assertObjectHasAttribute('data', $json);
        $this->assertObjectHasAttribute('id', $json->data);
        $this->assertObjectHasAttribute('name', $json->data);
        $this->assertObjectHasAttribute('thruster_power', $json->data);
        $this->assertObjectHasAttribute('turning_speed', $json->data);
        $this->assertObjectHasAttribute('kills', $json->data);
    }

    /**
     * @return Response
     */
    private function postDrone()
    {
        $data = [
            'name' => 'Drone POST Test',
            'squadron_id' => 1
        ];

        /** @var Response $response */
        $response = $this->client->post(
            '/api/v1/drones',
            [
                'body' => json_encode($data),
            ]
        );

        return $response;
    }
}
