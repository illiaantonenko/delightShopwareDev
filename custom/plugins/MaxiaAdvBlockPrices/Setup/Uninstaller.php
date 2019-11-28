<?php

namespace MaxiaAdvBlockPrices\Setup;

use Doctrine\DBAL\Driver\Connection;
use Shopware\Bundle\AttributeBundle\Service\CrudService;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Plugin\Context\UninstallContext;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Psr\Log\LoggerInterface;

class Uninstaller
{
    /** @var ContainerInterface $container */
    private $container;

    /** @var Connection $connection */
    private $connection;

    /** @var CrudService $container */
    private $crudService;

    /** @var ModelManager $container */
    private $modelManager;

    /** @var LoggerInterface */
    private $log;

    /**
     * Uninstaller constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->connection = $this->container->get('dbal_connection');
        $this->crudService = $this->container->get('shopware_attribute.crud_service');
        $this->modelManager = $this->container->get('models');
        $this->log = $this->container->get('pluginlogger');
    }

    /**
     * @param UninstallContext $context
     * @return bool
     */
    public function uninstall(UninstallContext $context)
    {
        try {
            if ($context->keepUserData()) {
                return true;
            }

            $this->connection->exec("DELETE FROM s_core_snippets WHERE namespace = 'frontend/plugins/maxia_adv_block_prices'");
            return true;

        } catch (\Exception $e) {
            $this->log->error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }
}