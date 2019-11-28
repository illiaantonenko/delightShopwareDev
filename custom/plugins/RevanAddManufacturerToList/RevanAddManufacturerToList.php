<?php

namespace RevanAddManufacturerToList;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;

class RevanAddManufacturerToList extends Plugin
{
    public function install(InstallContext $context)
    {

        $service = $this->container->get('shopware_attribute.crud_service');
        $service->update('s_articles_attributes', 'coming_date', 'date',[
            'label' => 'Product arrival date',
            'displayInBackend' => true,
            'custom' => true
        ]);
        parent::install($context);
    }

    public function uninstall(UninstallContext $context)
    {
        $service = $this->container->get('shopware_attribute.crud_service');
        $service->delete('s_articles_attributes', 'coming_date');
        parent::uninstall($context);
    }

}