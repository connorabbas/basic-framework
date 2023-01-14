<?php

namespace Tests\Feature;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

/**
 * @covers App\Core\Router
 * Test actual endpoints within the site
 * Site must be served
 * Testing locally only
 */
class RouterTest extends TestCase
{
    private $client;

    /**
     * https://codeception.com/docs/ContinuousIntegration
     * https://www.honeybadger.io/blog/php-testing/
     */

    public function setUp(): void
    {
        $this->client = new Client(['base_uri' => 'http://basic-framework.test/']);
    }

    public function test_registered_route()
    {
        $response = $this->client->get('/example');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_non_registered_route()
    {
        $response = $this->client->get('/abc/123', ['http_errors' => false]);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
