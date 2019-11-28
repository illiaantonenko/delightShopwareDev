<?php

namespace MaxiaAdvBlockPrices\Service;

use Doctrine\DBAL\Query\QueryBuilder;
use Shopware\Components\DependencyInjection\Container;
use Shopware\Components\DependencyInjection\ContainerAwareInterface;

/**
 * @package MaxiaAdvBlockPrices\Service
 */
class ConfigService implements ContainerAwareInterface
{
    /** @var Container $container */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function setContainer(Container $container = null)
    {
        $this->container = $container;
    }

    /**
     * Returns the plugin config
     *
     * @param bool $cache
     * @return mixed
     */
    public function getConfig($cache = true)
    {
        static $config;

        if (!$config || !$cache) {
            $config = $this->container->get('shopware.plugin.cached_config_reader')->getByPluginName('MaxiaAdvBlockPrices', Shopware()->Shop());

            if ($config['position'] == 'afterBuyButton' && !$config['showCurrentPrice']) {
                $config['showCurrentPrice'] = true;
            }
        }

        return $config;
    }

    /**
     * Returns a single config item value
     *
     * @param $key
     * @param null $default
     * @return null
     */
    public function get($key, $default = null)
    {
        $config = $this->getConfig();

        return isset($config[$key]) ? $config[$key] : null;
    }

    /**
     * Updates the plugin config
     *
     * @param $key
     * @param $value
     * @throws \Exception
     */
    public function updateConfig($key, $value)
    {
        static $shop = null;

        if ($shop === null) {
            $shop = $this->container->get('models')->getRepository('Shopware\Models\Shop\Shop')->findOneBy(['default' => true]);
        }

        $pluginManager = $this->container->get('shopware_plugininstaller.plugin_manager');
        $plugin = $pluginManager->getPluginByName('MaxiaAdvBlockPrices');

        $pluginManager->saveConfigElement($plugin, $key, $value, $shop);
    }
}