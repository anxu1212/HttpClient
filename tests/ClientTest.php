<?php


use anxu\HttpClient\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    /**
     * @var Client
     */
    protected $client;

    protected function setUp()
    {
        $this->client = new Client([
            'Content-type' => 'application/json'
        ]);
    }

    protected function tearDown()
    {
        $this->client = null;
    }

    public function testGet()
    {
        $response = $this->client->get('https://github.com/search', [
            'q' => 'test'
        ]);

        $this->assertEquals('200', $response->getStatus());
        $this->assertNotEmpty($response->getContent());
    }

    public function testPost()
    {
        $response = $this->client->post('https://api.github.com/users/test');

        $this->assertEquals('403', $response->getStatus());
        $this->assertNotEmpty($response->getContent());
        var_dump($response->getHeaders());
    }

}
