<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Collector;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface;
use Spryker\Zed\Collector\Business\Collector\AbstractDatabaseCollector;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use SprykerTest\Zed\Collector\Business\ArrayBatchIterator;
use Symfony\Component\Console\Output\OutputInterface as SymfonyOutputInterface;

class TestableAbstractDatabaseCollector extends AbstractDatabaseCollector
{
    public function startProgressBar(CountableIteratorInterface $batchCollection, BatchResultInterface $batchResult, SymfonyOutputInterface $output)
    {
        return parent::startProgressBar($batchCollection, $batchResult, $output);
    }

    /**
     * Minimal implementation to satisfy abstract contract for tests
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    protected function generateBatchIterator()
    {
        return new ArrayBatchIterator([]);
    }

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $touchQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function prepareCollectorScope(SpyTouchQuery $touchQuery, LocaleTransfer $localeTransfer)
    {
        // no-op for test stub
    }

    /**
     * @param mixed $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        return [];
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return 'test';
    }
}
