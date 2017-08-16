<?php
/**
 * Created by PhpStorm.
 * User: sarcoma
 * Date: 16/08/17
 * Time: 10:28
 */

namespace Tests;

use GuzzleHttp\Client;
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
}