<?php

namespace MaxiaAdvBlockPrices;

use MaxiaAdvBlockPrices\Setup\Installer;
use MaxiaAdvBlockPrices\Setup\Uninstaller;
use MaxiaAdvBlockPrices\Setup\Updater;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;

class MaxiaAdvBlockPrices extends \Shopware\Components\Plugin
{
    /**
     * {@inheritdoc}
     */
    public function install(InstallContext $context)
    {
        $installer = new Installer($this->container);
        return $installer->install($context);
    }

    /**
     * {@inheritdoc}
     */
    public function uninstall(UninstallContext $context)
    {
        $uninstaller = new Uninstaller($this->container);
        return $uninstaller->uninstall($context);
    }

    /**
     * {@inheritdoc}
     */
    public function update(UpdateContext $context)
    {
        $updater = new Updater($this->container);
        $result = $updater->update($context->getCurrentVersion(), $context);

        $context->scheduleClearCache([
            InstallContext::CACHE_TAG_TEMPLATE,
            InstallContext::CACHE_TAG_THEME,
            InstallContext::CACHE_TAG_PROXY,
            InstallContext::CACHE_TAG_CONFIG,
            InstallContext::CACHE_TAG_HTTP,
        ]);

		return $result;
	}

	public function activate(ActivateContext $context)
    {
        $context->scheduleClearCache([
            InstallContext::CACHE_TAG_TEMPLATE,
            InstallContext::CACHE_TAG_THEME,
            InstallContext::CACHE_TAG_PROXY,
            InstallContext::CACHE_TAG_CONFIG,
            InstallContext::CACHE_TAG_HTTP,
        ]);

        return true;
	}

	public function deactivate(DeactivateContext $context)
    {
        $context->scheduleClearCache([
            InstallContext::CACHE_TAG_TEMPLATE,
            InstallContext::CACHE_TAG_THEME,
            InstallContext::CACHE_TAG_PROXY,
            InstallContext::CACHE_TAG_CONFIG,
            InstallContext::CACHE_TAG_HTTP,
        ]);

        return true;
	}
}
