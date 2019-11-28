<?php

namespace RevanCronJobs;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;

class RevanCronJobs extends Plugin
{

    public function install(InstallContext $context)
    {

        $service = $this->container->get('shopware_attribute.crud_service');
        $service->update('s_articles_attributes', 'is_exported', 'boolean',[
            'label' => 'Is Exported',
            'displayInBackend' => true,
            'custom' => true
        ]);
        parent::install($context);
    }

    public function uninstall(UninstallContext $context)
    {
        $service = $this->container->get('shopware_attribute.crud_service');
        $service->delete('s_articles_attributes', 'is_exported');
        parent::uninstall($context);
    }

}