<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Persistence\Collector;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;

abstract class AbstractCollectorQuery
{
    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $locale;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected $storeTransfer;

    /**
     * @return void
     */
    abstract protected function prepareQuery();

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return $this
     */
    public function setLocale(LocaleTransfer $localeTransfer)
    {
        $this->locale = $localeTransfer;

        return $this;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreTransfer()
    {
        return $this->storeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return $this
     */
    public function setStoreTransfer($storeTransfer)
    {
        $this->storeTransfer = $storeTransfer;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    public function getTouchQueryContainer()
    {
        return $this->touchQueryContainer;
    }

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $touchQueryContainer
     *
     * @return $this
     */
    public function setTouchQueryContainer(TouchQueryContainerInterface $touchQueryContainer)
    {
        $this->touchQueryContainer = $touchQueryContainer;

        return $this;
    }

    /**
     * @return $this
     */
    public function prepare()
    {
        $this->prepareQuery();

        return $this;
    }
}
