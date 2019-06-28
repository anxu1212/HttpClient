<?php

namespace tests;

use anxu\HttpClient\ClientRequest;
use PHPUnit\Framework\TestCase;

class ClientRequestTest extends TestCase
{
    /**
     * @var ClientRequest
     */
    protected $client;

    protected function setUp()
    {
        $this->client = new ClientRequest(
            'https://github.com/search',
            ClientRequest::METHOD_GET,
            [
                'ie' => 'UTF-8',
                'wd' => 'test'
            ],
            'content',
            [
                'cookie' => [
                    'SESSIONID=d98aa7fe2dd4a8b0111374f84ca3941e',
                    'id=demo'
                ],
                'Content-type' => 'application/json'
            ]
        );
    }

    protected function tearDown()
    {
        $this->client = null;
    }


    public function testGetRawHeaders()
    {
        $this->assertSame([
            'cookie' => [
                'SESSIONID=d98aa7fe2dd4a8b0111374f84ca3941e',
                'id=demo'
            ],
            'Content-type' => 'application/json'
        ], $this->client->getRawHeaders());
    }

    public function testGetContent()
    {
        $this->assertEquals('content', $this->client->getContent());
    }

    public function testGetUri()
    {
        $this->assertEquals('https://github.com/search', $this->client->getUri());
    }

    public function testGetHeaders()
    {
        $this->assertSame([
            "cookie: SESSIONID=d98aa7fe2dd4a8b0111374f84ca3941e;id=demo",
            "Content-type: application/json"
        ], $this->client->getHeaders());
    }

    public function testGetMethod()
    {
        $this->assertEquals('GET', $this->client->getMethod());
    }

    public function testGetHeader()
    {
        $this->assertEquals('application/json', $this->client->getHeader('Content-type'));
        $this->assertEquals('SESSIONID=d98aa7fe2dd4a8b0111374f84ca3941e;id=demo', $this->client->getHeader('cookie'));
    }

    /**
     * @deprecated testGetParameter
     */
    public function testSetParameter()
    {
        $this->client->setParameter('name', 'test');
        $this->assertEquals('test', $this->client->getParameter('name'));
    }

    /**
     * @deprecated testGetHeader
     */
    public function testSetHeaders()
    {
        $this->client->setHeaders(['cookie' => ['id=test']]);
        $this->assertEquals('id=test', $this->client->getHeader('cookie'));

        $this->client->setHeaders(['cookie' => ['id=demo']], true);
        $this->assertEquals('id=test;id=demo', $this->client->getHeader('cookie'));

    }

    public function testGetParameter()
    {
        $this->assertEquals('UTF-8', $this->client->getParameter('ie'));
        $this->assertEquals('test', $this->client->getParameter('wd'));
    }

    /**
     * @deprecated  testGetHeader
     */
    public function testSetHeader()
    {
        $this->client->setHeader('Content-type', 'application/png');

        $this->assertEquals('application/png', $this->client->getHeader('Content-type'));
    }

    public function testGetParameters()
    {
        $this->assertSame([
            'ie' => 'UTF-8',
            'wd' => 'test'
        ], $this->client->getParameters());
    }

    public function testSetParameters()
    {
        $this->client->setParameters(['name' => 'test']);
        $this->assertSame(['name' => 'test'], $this->client->getParameters());

        $this->client->setParameters(['id' => '1'],true);
        $this->assertSame(['name' => 'test','id' => '1'], $this->client->getParameters());

    }
}
