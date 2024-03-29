<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Collector\Business\Exporter\Writer\Search;

use Codeception\Test\Unit;
use Elastica\Client;
use Elastica\Index;
use Elastica\Response;
use Elastica\Type;
use Spryker\Zed\Collector\Business\Exporter\Exception\InvalidDataSetException;
use Spryker\Zed\Collector\Business\Exporter\Writer\Search\ElasticsearchWriter;
use Spryker\Zed\Collector\Business\Index\IndexFactoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Collector
 * @group Business
 * @group Exporter
 * @group Writer
 * @group Search
 * @group ElasticsearchWriterTest
 * Add your own group annotations below this line
 */
class ElasticsearchWriterTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Elastica\Client
     */
    protected $client;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Elastica\Index
     */
    protected $index;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Elastica\Type
     */
    protected $type;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Collector\Business\Index\IndexFactoryInterface
     */
    protected $indexFactory;

    /**
     * @return void
     */
    public function testWriteCreateDocumentsWithValidDataSet(): void
    {
        $dataSet = $this->getValidTestDataSet();
        $writer = $this->getElasticsearchWriter();
        $this->assertTrue($writer->write($dataSet));
    }

    /**
     * @return void
     */
    public function testWriteCreateDocumentsWithInValidDataSet(): void
    {
        $this->expectException(InvalidDataSetException::class);
        $dataSet = $this->getInValidTestDataSet();
        $writer = $this->getElasticsearchWriter();
        $writer->write($dataSet);

        $this->expectException(InvalidDataSetException::class);
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->skipIfElasticsearch7();

        $this->type = $this->getMockType();
        $this->index = $this->getMockIndex();
        $this->client = $this->getMockClient();
        $this->indexFactory = $this->getMockIndexFactory();

        // now that index is setup, we can use it for mocking the Type class method getIndex
        $this->type->method('getIndex')->willReturn($this->index);
    }

    /**
     * Returns the valid data-set of array having non-numeric keys
     *
     * @return array
     */
    protected function getValidTestDataSet(): array
    {
        return [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
    }

    /**
     * Returns the invalid data-set of array having numeric keys
     *
     * @return array
     */
    protected function getInValidTestDataSet(): array
    {
        return ['value1', 'value2'];
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\Search\ElasticsearchWriter
     */
    protected function getElasticsearchWriter(): ElasticsearchWriter
    {
        return new ElasticsearchWriter($this->client, '', '', $this->indexFactory);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Elastica\Client
     */
    protected function getMockClient(): Client
    {
        $mockClient = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockClient->method('getIndex')->willReturn($this->index);

        return $mockClient;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Elastica\Index
     */
    protected function getMockIndex(): Index
    {
        $mockIndex = $this->getMockBuilder(Index::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockIndex->method('getType')->willReturn($this->type);
        $mockIndex->method('refresh')->willReturn($this->getResponse());

        return $mockIndex;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Elastica\Type
     */
    protected function getMockType(): Type
    {
        $mockType = $this->getMockBuilder(Type::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockType->method('addDocuments')->willReturn(null);

        return $mockType;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Collector\Business\Index\IndexFactoryInterface
     */
    protected function getMockIndexFactory(): IndexFactoryInterface
    {
        $mockIndexFactory = $this->getMockBuilder(IndexFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockIndexFactory->method('createIndex')->willReturn($this->index);

        return $mockIndexFactory;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Elastica\Response
     */
    protected function getResponse(): Response
    {
        $mockResponse = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockResponse->method('isOk')->willReturn(true);

        return $mockResponse;
    }

    /**
     * @return void
     */
    protected function skipIfElasticsearch7(): void
    {
        if (!method_exists(Index::class, 'getType')) {
            $this->markTestSkipped('This test is not suitable for Elasticsearch 7 or higher');
        }
    }
}
