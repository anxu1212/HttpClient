<?php

namespace tests;

use anxu\HttpClient\ClientResponse;
use PHPUnit\Framework\TestCase;

class ClientResponseTest extends TestCase
{

    /**
     * @var ClientResponse
     */
    protected $client;

    protected function setUp()
    {
        $this->client = new ClientResponse(
            'content',
            '200',
            [
                'Set-Cookie' => [
                    'SESSIONID=d98aa7fe2dd4a8b0111374f84ca3941e',
                    'id=demo'
                ],
                'Content-type' => 'text/html'
            ]
        );
    }

    protected function tearDown()
    {
        $this->client = null;
    }

    public function testGetHeader()
    {
        $this->assertEquals('text/html', $this->client->getHeader('Content-type'));
        $this->assertEquals('SESSIONID=d98aa7fe2dd4a8b0111374f84ca3941e', $this->client->getHeader('Set-Cookie'));
        $this->assertSame([
            'SESSIONID=d98aa7fe2dd4a8b0111374f84ca3941e',
            'id=demo'
        ],$this->client->getHeader('Set-Cookie',false));

    }


    public function testGetStatus()
    {
        $this->assertEquals('200', $this->client->getStatus());
    }

    public function testGetContent()
    {
        $this->assertEquals('content', $this->client->getContent());
    }

    public function testGetHeaders()
    {
        $this->assertSame(            [
            'Set-Cookie' => [
                'SESSIONID=d98aa7fe2dd4a8b0111374f84ca3941e',
                'id=demo'
            ],
            'Content-type' => 'text/html'
        ],$this->client->getHeaders());
    }
}
