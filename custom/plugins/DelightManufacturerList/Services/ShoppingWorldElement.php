<?php

namespace DelightManufacturerList\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ShoppingWorldElement
 * @package DelightManufacturerList\Services
 */
class ShoppingWorldElement
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /** @var string */
    private $pluginName;

    /**
     * ShoppingWorldElement constructor.
     * @param ContainerInterface $container
     * @param $pluginName
     */
    public function __construct(ContainerInterface $container, $pluginName)
    {
        $this->container = $container;
        $this->pluginName = $pluginName;
    }

    /**
     *
     */
    public function installElement()
    {
        $emotionComponentInstaller = $this->container->get('shopware.emotion_component_installer');
        try {
            $manufacturerElement = $emotionComponentInstaller->createOrUpdate($this->pluginName, "DelightManufacturerList", [
                'name' => 'Manufacturer List',
                'template' => 'emotion_manufacturer',
                'cls' => 'manufacturer-list-element',
                'description' => 'A custom element which performs manufacturer list.'
            ]);
            $manufacturerElement->createTextField([
                'name' => 'manufacturer_title',
                'fieldLabel' => 'Manufacturer Title',
                'supportText' => 'Enter the title which will be displayed for manufacturer list',
                'defaultValue' => 'Manufacturers'
            ]);
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }
}