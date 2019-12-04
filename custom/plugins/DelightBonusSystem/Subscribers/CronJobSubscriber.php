<?php

namespace DelightBonusSystem\Subscribers;

use DateTime;
use Enlight\Event\SubscriberInterface;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Plugin\ConfigReader;
use Shopware\Models\Customer\Customer;
use Shopware\Models\Order\Order;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CronJobSubscriber implements SubscriberInterface
{

    private $container;
    private $config;

    public function __construct(ContainerInterface $container, ConfigReader $configReader)
    {
        $this->container = $container;
        $this->config = $configReader;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Shopware_CronJob_CheckTokensActuality' => 'onCheckTokensActuality',
        ];
    }

    public function onCheckTokensActuality(\Shopware_Components_Cron_CronJob $job)
    {
        /**
         * @var $modelManager ModelManager
         * @var $order Order
         * @var $customer Customer
         */
        $modelManager = $this->container->get('models');
        $orders = $modelManager->getRepository(Order::class)->findAll();

        foreach ($orders as $order) {
            echo $order->getId();
            if (!$order->getTokensAccrued()) {
                $nowDate = new DateTime();
                if ($nowDate->diff($order->getClearedDate())['d'] > $this->config['delightTokensHoldTime']) {
                    $sumTokens = 0;
                    foreach ($order->getDetails() as $detail) {
                        $sumTokens += $detail->getInBonusProgram() ? round($detail->getCostInToken(), 2) : 0;
                    }
                    $customer = $order->getCustomer();
                    $customer->setTokens($customer->getTokens() + $sumTokens);
                    $order->setTokensAccrued(1);
                    $order->setTokensSum($sumTokens);
                    $modelManager->persist($customer);
                    $modelManager->persist($order);
                    $modelManager->flush();
                }
            }
        }
    }

}