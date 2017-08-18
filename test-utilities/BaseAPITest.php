<?php
/**
 * Created by PhpStorm.
 * User: sarcoma
 * Date: 16/08/17
 * Time: 10:28
 */

namespace TestUtilities;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Slim\Http\Request;

class BaseAPITest extends \PHPUnit_Framework_TestCase
{

    private static $staticClient;

    /**
     * @var Client $client
     */
    protected $client;

    public static function setUpBeforeClass()
    {
        self::$staticClient = new Client([
            'base_uri' => 'http://localhost:8000',
            'defaults' => [
                'exceptions' => false
            ]
        ]);
    }

    public function setup() {
        $this->client = self::$staticClient;
    }

    /**
     * @param Response $response
     * @param $json
     * @param int $statusCode
     */
    protected function successStatusAsserts(Response $response, $json, $statusCode = 200)
    {
        $this->assertEquals($statusCode, $response->getStatusCode());
        $this->assertObjectHasAttribute('status', $json);
        $this->assertEquals('success', $json->status);
        $this->assertObjectHasAttribute('messages', $json);
    }

    /**
     * @param Response $response
     * @param $json
     * @param int $statusCode
     */
    protected function errorStatusAsserts(Response $response, $json, $statusCode = 400)
    {
        $this->assertEquals($statusCode, $response->getStatusCode());
        $this->assertObjectHasAttribute('status', $json);
        $this->assertEquals('error', $json->status);
        $this->assertObjectHasAttribute('errors', $json);
    }
}