<?php

namespace MaxiaAdvBlockPrices\Setup;

use MaxiaAdvBlockPrices\Service\ConfigService;
use Shopware\Bundle\AttributeBundle\Service\CrudService;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Plugin\Context\UpdateContext;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Psr\Log\LoggerInterface;

class Updater
{
    /** @var ContainerInterface $container */
    private $container;

    /** @var CrudService $crudService */
    private $crudService;

    /** @var ModelManager $container */
    private $modelManager;

    /** @var \Shopware_Components_Snippet_Manager $snippets */
    private $snippets;

    /** @var ConfigService $config */
    private $config;

    /** @var Installer $installer */
    private $installer;

    /** @var LoggerInterface */
    private $log;

    /**
     * Updater constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->snippets = $this->container->get('snippets');
        $this->crudService = $this->container->get('shopware_attribute.crud_service');
        $this->modelManager = $this->container->get('models');
        $this->config = $this->container->get('maxia.adv_block_prices.config_service');
        $this->log = $this->container->get('pluginlogger');

        $this->installer = new Installer($container);
    }

    /**
     * @param $oldVersion
     * @param UpdateContext $context
     * @return bool
     */
    public function update($oldVersion, UpdateContext $context)
    {
        try {
            if (version_compare($oldVersion, '1.1.0', '<')) {
                $this->config->updateConfig('showUntil', 0);
                $this->config->updateConfig('blockPricesClickable', 1);
                $this->config->updateConfig('showTotal', 0);
                $this->config->updateConfig('showTotalBlockPricesOnly', 0);
            }

            if (version_compare($oldVersion, '1.1.1', '<')) {
                $this->config->updateConfig('showPseudoprice', 1);
                $this->config->updateConfig('pluginEnabled', 1);
            }

            if (version_compare($oldVersion, '1.2.0', '<')) {
                $this->config->updateConfig('showTransparency', 0);
            }

            if (version_compare($oldVersion, '1.3.0', '<')) {
                $this->config->updateConfig('discountAsBadge', 0);
            }

            if (version_compare($oldVersion, '1.5.0', '<')) {
                $this->config->updateConfig('scrollable', 0);
                $this->config->updateConfig('quantitySelectTheme', 'default');
                $this->config->updateConfig('stylesActiveRowIndicatorWidth', '4');
                $this->config->updateConfig('stylesRowHeight', '60');
                $this->config->updateConfig('stylesRowHeightActive', '75');
            }

            if (version_compare($oldVersion, '1.5.1', '<')) {
                $this->config->updateConfig('scrollable', 0);
                $this->config->updateConfig('quantitySelectTheme', 'default');
                $this->config->updateConfig('stylesActiveRowIndicatorWidth', '4');
                $this->config->updateConfig('stylesRowHeight', '60');
                $this->config->updateConfig('stylesRowHeightActive', '75');
            }

            if (version_compare($oldVersion, '1.5.2', '<')) {
                $this->config->updateConfig('showNumberInputPopups', 0);
                $this->config->updateConfig('showTotalAsPopup', 0);
            }

            if (version_compare($oldVersion, '1.6.0', '<')) {
                $this->config->updateConfig('highlightPrice', 1);
                $this->config->updateConfig('showCurrentPrice', 0);
            }

            if (version_compare($oldVersion, '1.6.0', '<')) {
                $this->config->updateConfig('highlightPrice', 1);
                $this->config->updateConfig('showCurrentPrice', 0);
            }

            return true;

        } catch (\Exception $e) {
            $this->log->error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }
}