<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Collector\Business\CollectorFacade getFacade()
 * @method \Spryker\Zed\Collector\Communication\CollectorCommunicationFactory getFactory()
 */
abstract class AbstractCollectorPlugin extends AbstractPlugin implements CollectorPluginInterface
{

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface
     */
    protected $dataWriter;

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface
     */
    protected $touchUpdater;

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface
     */
    public function getDataWriter()
    {
        return $this->dataWriter;
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $dataWriter
     *
     * @return void
     */
    public function setDataWriter(WriterInterface $dataWriter)
    {
        $this->dataWriter = $dataWriter;
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface
     */
    public function getTouchUpdater()
    {
        return $this->touchUpdater;
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     *
     * @return void
     */
    public function setTouchUpdater(TouchUpdaterInterface $touchUpdater)
    {
        $this->touchUpdater = $touchUpdater;
    }

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $baseQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $result
     *
     * @return void
     */
    abstract public function run(
        SpyTouchQuery $baseQuery,
        LocaleTransfer $locale,
        BatchResultInterface $result
    );

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $baseQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $result
     *
     * @return void
     */
    public function postRun(
        SpyTouchQuery $baseQuery,
        LocaleTransfer $locale,
        BatchResultInterface $result
    ) {
    }

}
