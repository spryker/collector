<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter;

use DateTime;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\CollectorConfig;

class ExportMarker implements MarkerInterface
{
    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface
     */
    protected $writer;

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface
     */
    protected $reader;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @var \Spryker\Zed\Collector\CollectorConfig
     */
    protected $collectorConfig;

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $writer
     * @param \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface $reader
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $keyBuilder
     * @param \Spryker\Zed\Collector\CollectorConfig $collectorConfig
     */
    public function __construct(
        WriterInterface $writer,
        ReaderInterface $reader,
        KeyBuilderInterface $keyBuilder,
        CollectorConfig $collectorConfig
    ) {
        $this->writer = $writer;
        $this->reader = $reader;
        $this->keyBuilder = $keyBuilder;
        $this->collectorConfig = $collectorConfig;
    }

    /**
     * @param string $exportType
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \DateTime
     */
    public function getLastExportMarkByTypeAndLocale($exportType, LocaleTransfer $localeTransfer)
    {
        /** @var string|null $lastTimeStamp */
        $lastTimeStamp = $this->reader
            ->read($this->keyBuilder->generateKey($exportType, $localeTransfer->getLocaleName()), $exportType);

        if (!$lastTimeStamp) {
            $lastTimeStamp = '2000-01-01 00:00:00';
        }

        return DateTime::createFromFormat('Y-m-d H:i:s', $lastTimeStamp);
    }

    /**
     * @param string $exportType
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \DateTime $timestamp
     *
     * @return void
     */
    public function setLastExportMarkByTypeAndLocale($exportType, LocaleTransfer $localeTransfer, DateTime $timestamp)
    {
        $timestampKey = $this->keyBuilder->generateKey($exportType, $localeTransfer->getLocaleName());
        $this->writer->write([$timestampKey => $timestamp->format('Y-m-d H:i:s')]);
    }

    /**
     * @param array $keys
     *
     * @return bool
     */
    public function deleteTimestamps(array $keys)
    {
        if (!$this->collectorConfig->isCollectorEnabled()) {
            return true;
        }

        return $this->writer->delete($keys);
    }
}
