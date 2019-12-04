<?php

namespace DelightBonusSystem\Subscribers;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Enlight_Controller_Request_RequestHttp;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Order\Order;
use Doctrine\ORM\Event\LifecycleEventArgs;

class BackendSubscriber implements \Enlight\Event\SubscriberInterface
{

    private $pluginDirectory;
    private $container;

    public function __construct($pluginDirectory, $container)
    {
        error_log('construct');
        $this->pluginDirectory = $pluginDirectory;
        $this->container = $container;
    }


    public static function getSubscribedEvents()
    {
        return array(
            'Shopware_Controllers_Backend_Order::saveAction::before' => 'saveAction'
        );
    }

    public function saveAction(\Enlight_Hook_HookArgs $args)
    {
        /* @var \Shopware_Controllers_Backend_Order $subject */
        $subject = $args->getSubject();

        $params = $subject->Request()->getParams();
        $previousPaymentStatus = $params['paymentStatus'][0]['id'];

        /* @var Order $currentOrder */
        $currentOrder = $this->container->get('models')->getRepository(Order::class)->findOneBy(['id' => $params['id']]);
        $currentPaymentStatus = $currentOrder->getPaymentStatus()->getId();

        $flag = $previousPaymentStatus == $currentPaymentStatus ? true : false;
        if (!$flag) {
            /* Your logic */
        }
    }
}