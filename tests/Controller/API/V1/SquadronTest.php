<?php
/**
 * Created by PhpStorm.
 * User: sarcoma
 * Date: 17/08/17
 * Time: 12:22
 */

namespace Tests\Controller\API\V1;

use TestUtilities\BaseAPITest;

class SquadronTest extends BaseAPITest
{
    public function testGET()
    {
        $response = $this->client->get('/api/v1/squadrons/1');
        $json = json_decode($response->getBody());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertObjectHasAttribute('status', $json);
        $this->assertEquals('success', $json->status);
        $this->assertObjectHasAttribute('data', $json);
        $this->assertObjectHasAttribute('id', $json->data);
        $this->assertObjectHasAttribute('name', $json->data);
        $this->assertObjectHasAttribute('cash', $json->data);
        $this->assertObjectHasAttribute('drones', $json->data);
    }

    public function testGETNotFound()
    {
        $response = $this->client->get('/api/v1/squadrons/0', ['http_errors' => false]);
        $json = json_decode($response->getBody());
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertObjectHasAttribute('status', $json);
        $this->assertEquals('error', $json->status);
        $this->assertObjectHasAttribute('errors', $json);
    }

    public function testPOST()
    {
        $data = [
            'name' => 'Squadron Test',
        ];

        $response = $this->client->post('/api/v1/squadrons',[
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
        ];

        $response = $this->client->post('/api/v1/squadrons',[
            'body' => json_encode($data)
        ]);

        $json = json_decode($response->getBody());
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertObjectHasAttribute('status', $json);
        $this->assertEquals('error', $json->status);
        $this->assertObjectHasAttribute('errors', $json);
    }
}
