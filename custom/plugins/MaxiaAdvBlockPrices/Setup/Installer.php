<?php

namespace MaxiaAdvBlockPrices\Setup;

use Shopware\Bundle\AttributeBundle\Service\CrudService;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Plugin\Context\InstallContext;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Psr\Log\LoggerInterface;

class Installer
{
    /** @var ContainerInterface $container */
    private $container;

    /** @var CrudService $crudService */
    private $crudService;

    /** @var ModelManager $container */
    private $modelManager;

    /** @var \Shopware_Components_Snippet_Manager $snippets */
    private $snippets;

    /** @var LoggerInterface */
    private $log;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->snippets = $this->container->get('snippets');
        $this->crudService = $this->container->get('shopware_attribute.crud_service');
        $this->modelManager = $this->container->get('models');
        $this->log = $this->container->get('pluginlogger');
    }

    /**
     * @param InstallContext $context
     * @return mixed
     */
    public function install(InstallContext $context)
    {
        try {
            return true;

        } catch (\Exception $e) {
            $this->log->error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }
}