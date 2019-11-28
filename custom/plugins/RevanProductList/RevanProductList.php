<?php


namespace RevanProductList;


use Shopware\Components\Emotion\ComponentInstaller;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;

class RevanProductList extends Plugin
{
    public function install(InstallContext $context)
    {
        parent::install($context);
        /**@var $emotionComponentInstaller ComponentInstaller*/
        $emotionComponentInstaller = $this->container->get('shopware.emotion_component_installer');

        $customElement = $emotionComponentInstaller->createOrUpdate(
            $this->getName(),
            'RevanManufacturersElement',
            [
                'name' => 'Manufacturers List',
                'template' => 'emotion_manufacturers',
                'cls' => 'emotion-manufacturers-element',
                'description' => 'A simple manufacturers list element for the shopping worlds.'
            ]
        );
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