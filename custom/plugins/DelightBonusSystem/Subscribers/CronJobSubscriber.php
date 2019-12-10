<?php
/*
 * Subscriber provides cron job for checking all orders with not accrued tokens
 * to provide delay from paying to gaining bonus tokens
 */

namespace DelightBonusSystem\Subscribers;

use DateTime;
use Enlight\Event\SubscriberInterface;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Plugin\ConfigReader;
use Shopware\Models\Attribute\OrderDetail;
use Shopware\Models\Customer\Customer;
use Shopware\Models\Order\Detail;
use Shopware\Models\Order\Order;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CronJobSubscriber
 * @package DelightBonusSystem\Subscribers
 */
class CronJobSubscriber implements SubscriberInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var array
     */
    private $config;

    /**
     * CronJobSubscriber constructor.
     * @param $pluginName
     * @param ContainerInterface $container
     * @param ConfigReader $configReader
     */
    public function __construct($pluginName, ContainerInterface $container, ConfigReader $configReader)
    {
        $this->container = $container;
        $this->config = $configReader->getByPluginName($pluginName);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Shopware_CronJob_CheckTokensActuality' => 'onCheckTokensActuality',
        ];
    }

    /**
     * @param \Shopware_Components_Cron_CronJob $job
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onCheckTokensActuality(\Shopware_Components_Cron_CronJob $job)
    {
        /**
         * @var $modelManager ModelManager
         * @var $order Order
         * @var $customer Customer
         */
        $modelManager = $this->container->get('models');
        $orders = $modelManager->createQueryBuilder()->select(['orders', 'attribute'])
            ->from(Order::class, 'orders')
            ->innerJoin('orders.attribute', 'attribute')
            ->where('attribute.tokensAccrued = :false')
            ->setParameter('false', false)->getQuery()->getResult();

        foreach ($orders as $order) {
            /**
             * @var \Shopware\Models\Attribute\Order $orderAttribute
             * @var \Shopware\Models\Attribute\Article $detailAttribute
             * @var \Shopware\Models\Attribute\Customer $customerAttribute
             */
            $orderAttribute = $order->getAttribute();

            if (!$orderAttribute->getTokensAccrued()) {

                $nowDate = new DateTime();
                if ($nowDate->diff($order->getClearedDate())->d > $this->config['delightTokensHoldTime']) {
                    $sumTokens = 0;
                    /** @var $detail Detail */
                    foreach ($order->getDetails() as $detail) {
                        $detailAttribute = $detail->getArticleDetail()->getAttribute();

                        $sumTokens += $detailAttribute->getInBonusProgram() ? round($detailAttribute->getTokenForPurchase(), 2) : 0;
                    }
                    $customer = $order->getCustomer();
                    $customerAttribute = $customer->getAttribute();
                    $customerAttribute->setTokens($customerAttribute->getTokens() + $sumTokens);
                    $orderAttribute->setTokensAccrued(1);
                    $orderAttribute->setTokensSum($sumTokens);
                    $modelManager->persist($customer);
                    $modelManager->persist($order);
                    $modelManager->flush();
                }
            }
        }
    }

}