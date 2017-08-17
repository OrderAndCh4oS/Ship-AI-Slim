<?php
/**
 * Created by PhpStorm.
 * User: sarcoma
 * Date: 16/08/17
 * Time: 10:11
 */

namespace Tests\Controller\API\V1;

use TestUtilities\BaseAPITest;
use GuzzleHttp;

class DronesTest extends BaseAPITest
{
    public function testGET()
    {
        $response = $this->client->get('/api/v1/drones');
        $json = json_decode($response->getBody());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertObjectHasAttribute('status', $json);
        $this->assertEquals('success', $json->status);
        $this->assertObjectHasAttribute('data', $json);
        $this->assertObjectHasAttribute('id', $json->data);
        $this->assertObjectHasAttribute('name', $json->data);
        $this->assertObjectHasAttribute('thruster_power', $json->data);
        $this->assertObjectHasAttribute('turning_speed', $json->data);
        $this->assertObjectHasAttribute('kills', $json->data);
    }

    public function testGETOne()
    {
        $response = $this->client->get('/api/v1/drones/1');
        $json = json_decode($response->getBody());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertObjectHasAttribute('status', $json);
        $this->assertEquals('success', $json->status);
        $this->assertObjectHasAttribute('data', $json);
        $this->assertObjectHasAttribute('id', $json->data);
        $this->assertObjectHasAttribute('name', $json->data);
        $this->assertObjectHasAttribute('thruster_power', $json->data);
        $this->assertObjectHasAttribute('turning_speed', $json->data);
        $this->assertObjectHasAttribute('kills', $json->data);
    }

    public function testGETNotFound()
    {
        $response = $this->client->get('/api/v1/drones/0', ['http_errors' => false]);
        $json = json_decode($response->getBody());
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertObjectHasAttribute('status', $json);
        $this->assertEquals('error', $json->status);
        $this->assertObjectHasAttribute('errors', $json);
    }

    public function testPOST()
    {
        $data = [
            'name' => 'Drone Test',
            'squadron_id' => 1
        ];

        $response = $this->client->post('/api/v1/drones',[
            'body' => json_encode($data)
        ]);
        $json = json_decode($response->getBody());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertObjectHasAttribute('status', $json);
        $this->assertEquals('success', $json->status);
        $this->assertObjectHasAttribute('data', $json);
        $this->assertObjectHasAttribute('message', $json->data);
    }

    public function testPOSTInvalidData()
    {
        $data = [
            'name' => '',
            'squadron_id' => 0
        ];

        $response = $this->client->post('/api/v1/drones',[
            'body' => json_encode($data)
        ]);

        $json = json_decode($response->getBody());
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertObjectHasAttribute('status', $json);
        $this->assertEquals('error', $json->status);
        $this->assertObjectHasAttribute('errors', $json);
    }


}