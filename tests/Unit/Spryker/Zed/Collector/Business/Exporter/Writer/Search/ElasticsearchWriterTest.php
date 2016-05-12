<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Collector\Business\Exporter\Writer\Search;

use Elastica\Client;
use Elastica\Index;
use Elastica\Response;
use Elastica\Type;
use Spryker\Zed\Collector\Business\Exporter\Exception\InvalidDataSetException;
use Spryker\Zed\Collector\Business\Exporter\Writer\Search\ElasticsearchWriter;

class ElasticsearchWriterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Elastica\Client
     */
    protected $client;

    /**
     * @var \Elastica\Index
     */
    protected $index;

    /**
     * @var \Elastica\Type
     */
    protected $type;

    /**
     * @return void
     */
    public function testWriteCreateDocumentsWithValidDataSet()
    {
        $dataSet = $this->getValidTestDataSet();
        $writer = $this->getElasticsearchWriter();
        $this->assertTrue($writer->write($dataSet));
    }

    /**
     * @expectedException \Spryker\Zed\Collector\Business\Exporter\Exception\InvalidDataSetException
     * @return void
     */
    public function testWriteCreateDocumentsWithInValidDataSet()
    {
        $dataSet = $this->getInValidTestDataSet();
        $writer = $this->getElasticsearchWriter();
        $writer->write($dataSet);

        $this->expectException(InvalidDataSetException::class);
    }

    /**
     * @return void
     */
    public function setUp()
    {
        $this->type = $this->getMockType();
        $this->index = $this->getMockIndex();
        $this->client = $this->getMockClient();

        // now that index is setup, we can use it for mocking the Type class method getIndex
        $this->type->method('getIndex')->willReturn($this->index);
    }

    /**
     * Returns the valid data-set of array having non-numeric keys
     * @return array
     */
    private function getValidTestDataSet()
    {
        return [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
    }

    /**
     * Returns the invalid data-set of array having numeric keys
     * @return array
     */
    private function getInValidTestDataSet()
    {
        return ['value1', 'value2'];
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\Search\ElasticsearchWriter
     */
    private function getElasticsearchWriter()
    {
        return new ElasticsearchWriter($this->client, '', '');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Elastica\Client
     */
    private function getMockClient()
    {
        $mockClient = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockClient->method('getIndex')->willReturn($this->index);

        return $mockClient;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Elastica\Index
     */
    private function getMockIndex()
    {
        $mockIndex = $this->getMockBuilder(Index::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockIndex->method('getType')->willReturn($this->type);
        $mockIndex->method('refresh')->willReturn($this->getResponse());

        return $mockIndex;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Elastica\Type
     */
    private function getMockType()
    {
        $mockType = $this->getMockBuilder(Type::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockType->method('addDocuments')->willReturn(null);

        return $mockType;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Elastica\Response
     */
    private function getResponse()
    {
        $mockResponse = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockResponse->method('isOk')->willReturn(true);
        return $mockResponse;
    }

}
