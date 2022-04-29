<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\Search;

use Elastica\Client;
use Elastica\Document;
use Elastica\Exception\NotFoundException;
use Generated\Shared\Transfer\SearchCollectorConfigurationTransfer;
use Spryker\Zed\Collector\Business\Exporter\Exception\InvalidDataSetException;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Index\IndexFactoryInterface;

class ElasticsearchWriter implements WriterInterface, ConfigurableSearchWriterInterface
{
    /**
     * @var \Elastica\Client
     */
    protected $client;

    /**
     * @var \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer
     */
    protected $searchCollectorConfiguration;

    /**
     * @var \Spryker\Zed\Collector\Business\Index\IndexFactoryInterface
     */
    protected $indexFactory;

    /**
     * @param \Elastica\Client $searchClient
     * @param string $indexName
     * @param string $type
     * @param \Spryker\Zed\Collector\Business\Index\IndexFactoryInterface $indexFactory
     */
    public function __construct(Client $searchClient, $indexName, $type, IndexFactoryInterface $indexFactory)
    {
        $this->client = $searchClient;

        $this->searchCollectorConfiguration = new SearchCollectorConfigurationTransfer();
        $this->searchCollectorConfiguration
            ->setIndexName($indexName)
            ->setTypeName($type);

        $this->indexFactory = $indexFactory;
    }

    /**
     * @param array<string, mixed> $dataSet
     *
     * @throws \Spryker\Zed\Collector\Business\Exporter\Exception\InvalidDataSetException
     *
     * @return bool
     */
    public function write(array $dataSet)
    {
        if ($this->hasIntegerKeys($dataSet)) {
            throw new InvalidDataSetException();
        }

        $documents = $this->createDocuments($dataSet);
        $this->getIndex()->addDocuments($documents);
        $response = $this->getIndex()->refresh();

        return $response->isOk();
    }

    /**
     * @param array<string, mixed> $dataSet
     *
     * @throws \Spryker\Zed\Collector\Business\Exporter\Exception\InvalidDataSetException
     *
     * @return bool
     */
    public function delete(array $dataSet)
    {
        if ($this->hasIntegerKeys($dataSet)) {
            throw new InvalidDataSetException();
        }

        $documents = [];
        $keys = array_keys($dataSet);
        foreach ($keys as $key) {
            try {
                /** @var \Spryker\Zed\Collector\Business\Index\IndexAdapterInterface $index */
                $index = $this->getIndex();
                $documents[] = $index
                    ->getDocument($key, ['routing' => $key])
                    ->setRouting($key);
            } catch (NotFoundException $e) {
                continue;
            }
        }

        if (!$documents) {
            return true;
        }

        $response = $this->getIndex()->deleteDocuments($documents);
        $this->getIndex()->flush();

        return $response->isOk();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'elasticsearch-writer';
    }

    /**
     * @param array<string, mixed> $dataSet
     *
     * @throws \Spryker\Zed\Collector\Business\Exporter\Exception\InvalidDataSetException
     *
     * @return array
     */
    protected function createDocuments(array $dataSet)
    {
        if ($this->hasIntegerKeys($dataSet)) {
            throw new InvalidDataSetException();
        }

        $documentPrototype = new Document();
        $documents = [];

        foreach ($dataSet as $key => $data) {
            $document = clone $documentPrototype;

            if (is_array($data)) {
                $this->setParent($document, $data);
            }

            $document->setId($key);
            $document->setData($data);
            $documents[] = $document;
        }

        return $documents;
    }

    /**
     * Checks if the given array has any integer based (non-textual) keys
     *
     * @param array $array
     *
     * @return bool
     */
    protected function hasIntegerKeys(array $array)
    {
        return count(array_filter(array_keys($array), 'is_int')) > 0;
    }

    /**
     * @return \Elastica\Index|\Spryker\Zed\Collector\Business\Index\IndexAdapterInterface
     */
    protected function getIndex()
    {
        return $this->indexFactory->createIndex($this->client, $this->getSearchCollectorConfiguration());
    }

    /**
     * @param \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer $collectorConfigurationTransfer
     *
     * @return void
     */
    public function setSearchCollectorConfiguration(SearchCollectorConfigurationTransfer $collectorConfigurationTransfer)
    {
        $this->searchCollectorConfiguration->fromArray($collectorConfigurationTransfer->modifiedToArray());
    }

    /**
     * @return \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer
     */
    public function getSearchCollectorConfiguration()
    {
        return $this->searchCollectorConfiguration;
    }

    /**
     * @param \Elastica\Document $document
     * @param array<string, mixed> $data
     *
     * @return void
     */
    protected function setParent(Document $document, array $data)
    {
        if (!method_exists($document, 'setParent')) {
            return;
        }

        if (isset($data['parent'])) {
            $document->setParent($data['parent']);
        }
    }
}
