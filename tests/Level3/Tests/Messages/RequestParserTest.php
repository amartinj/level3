<?php

namespace Level3\Tests\Messages;

use Level3\Messages\Request;
use Level3\Messages\RequestParser;
use Mockery as m;

class RequestParserTest extends \PHPUnit_Framework_TestCase
{
    const IRRELEVANT_CONTENT = 'XY';
    const IRRELEVANT_CONTENT_TYPE = '3X';
    const IRRELEVANT_PATH_INFO = 'Y';
    const IRRELEVANT_KEY = 'YY';

    private $parserFactoryMock;

    private $requestParser;

    public function setUp()
    {
        $this->parserFactoryMock = m::mock('Level3\Messages\Parser\ParserFactory');

        $this->requestParser = new RequestParser($this->parserFactoryMock);
    }

    public function tearDown()
    {
        $this->parserFactoryMock = null;
        $this->requestParser = null;
    }

    public function testGetRequestContentAsArray()
    {
        $headers = array('content-type' => self::IRRELEVANT_CONTENT_TYPE);
        $this->createRequestWithHeaders($headers);
        $parser = m::mock('Level3\Messages\Parser\Parser');
        $this->parserFactoryMock->shouldReceive('create')->with(self::IRRELEVANT_CONTENT_TYPE)->once()->andReturn($parser);
        $parser->shouldreceive('parse')->with(self::IRRELEVANT_CONTENT)->once()->andreturn(array());

        $arrayContent = $this->requestParser->getRequestContentAsArray($this->request);

        $this->assertThat($arrayContent, $this->equalTo(array()));
    }

    private function createRequestWithHeaders($headers)
    {
        $this->request = new Request(self::IRRELEVANT_PATH_INFO, self::IRRELEVANT_KEY, $headers, array(), self::IRRELEVANT_CONTENT);
    }
}