<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use Symfony\Component\Console\Output\OutputInterface;

interface ExporterInterface
{

    /**
     * @param string $type
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Spryker\Zed\Collector\Business\Model\BatchResultInterface
     */
    public function exportByType($type, LocaleTransfer $locale, OutputInterface $output = null);

    /**
     * @return \Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginInterface[]
     */
    public function getCollectorPlugins();

}
