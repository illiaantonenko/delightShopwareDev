<?php


namespace DelightManufacturerList;


use DelightManufacturerList\Services\ShoppingWorldElement;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;

class DelightManufacturerList extends Plugin
{
    public function install(InstallContext $context)
    {
        $shoppingWorldInstaller = new ShoppingWorldElement($this->container, $this->getName());
        $shoppingWorldInstaller->installElement();
    }

    public function activate(ActivateContext $activateContext)
    {
        $activateContext->scheduleClearCache(ActivateContext::CACHE_LIST_ALL);
    }

    public function deactivate(DeactivateContext $deactivateContext)
    {
        $deactivateContext->scheduleClearCache(DeactivateContext::CACHE_LIST_ALL);
    }

    public function uninstall(UninstallContext $uninstallContext)
    {
        $uninstallContext->scheduleClearCache(UninstallContext::CACHE_LIST_ALL);
    }
}