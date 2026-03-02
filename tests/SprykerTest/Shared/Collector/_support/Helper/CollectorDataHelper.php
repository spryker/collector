<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Collector\Helper;

use Codeception\Module;
use Codeception\Stub;
use DateTime;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResult;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use Symfony\Component\Console\Output\NullOutput;

class CollectorDataHelper extends Module
{
    use LocatorHelperTrait;

    public function runCollector(
        AbstractFacade $facade,
        string $facadeCollectorMethod,
        string $resourceType,
        DateTime $lastTouchedAt
    ): array {
        $localeTransfer = $this->getLocaleFacade()->getCurrentLocale();

        $baseQuery = $this->createTouchBaseQuery($resourceType, $localeTransfer, $lastTouchedAt);

        $collectedData = [];
        $writerMock = Stub::constructEmpty(
            WriterInterface::class,
            [],
            [
                'write' => function ($data) use (&$collectedData) {
                    $collectedData[] = $data;

                    return $data;
                },
            ],
        );

        $facade->$facadeCollectorMethod(
            $baseQuery,
            $localeTransfer,
            $this->createBatchResult(),
            $this->getDataReaderMock(),
            $writerMock,
            $this->getTouchUpdaterMock(),
            $this->createNullOutput(),
        );

        return $collectedData;
    }

    public function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getLocator()->locale()->facade();
    }

    public function getTouchQueryContainer(): TouchQueryContainerInterface
    {
        return $this->getLocator()->touch()->queryContainer();
    }

    protected function getDataReaderMock(): ReaderInterface
    {
        return Stub::constructEmpty(ReaderInterface::class);
    }

    protected function getTouchUpdaterMock(): TouchUpdaterInterface
    {
        return Stub::constructEmpty(TouchUpdaterInterface::class);
    }

    protected function createTouchBaseQuery(string $resourceType, LocaleTransfer $localeTransfer, DateTime $lastTouchedAt): ModelCriteria
    {
        return $this->getTouchQueryContainer()
            ->createBasicExportableQuery(
                $resourceType,
                $localeTransfer,
                $lastTouchedAt,
            )
            ->withColumn(SpyTouchTableMap::COL_ID_TOUCH, CollectorConfig::COLLECTOR_TOUCH_ID)
            ->withColumn(SpyTouchTableMap::COL_ITEM_ID, CollectorConfig::COLLECTOR_RESOURCE_ID)
            ->setFormatter(new PropelArraySetFormatter());
    }

    protected function createNullOutput(): NullOutput
    {
        return new NullOutput();
    }

    protected function createBatchResult(): BatchResult
    {
        return new BatchResult();
    }
}
