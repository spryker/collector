<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector;

use Spryker\Zed\Collector\Dependency\Facade\CollectorToLocaleBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CollectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_LOCALE = 'locale facade';
    const QUERY_CONTAINER_TOUCH = 'touch query container';
    const SEARCH_PLUGINS = 'search plugins';
    const STORAGE_PLUGINS = 'storage plugins';

    /**
     * @var Container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->provideLocaleFacade($container);

        $container[self::QUERY_CONTAINER_TOUCH] = function (Container $container) {
            return $container->getLocator()->touch()->queryContainer();
        };

        return $container;
    }

    /**
     * @var Container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->provideLocaleFacade($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    private function provideLocaleFacade(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new CollectorToLocaleBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

}