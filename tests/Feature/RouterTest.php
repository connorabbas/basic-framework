<?php

namespace Tests\Feature;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

/**
 * Test actual endpoints within the site
 * Site must be served
 */
class RouterTest extends TestCase
{
    private $client;

    /* public function setUp(): void
    {
        $this->client = new Client(['base_uri' => 'http://127.0.0.1:8000']);
    }

    public function testRegisteredRoute()
    {
        $response = $this->client->get('/example');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNonRegisteredRoute()
    {
        $response = $this->client->get('/abc/123', ['http_errors' => false]);
        $this->assertEquals(404, $response->getStatusCode());
    } */
}
