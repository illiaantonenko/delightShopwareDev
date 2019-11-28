<?php

namespace MaxiaAdvBlockPrices\Subscriber;

use Enlight\Event\SubscriberInterface;
use MaxiaAdvBlockPrices\Components\StructConverter;
use MaxiaAdvBlockPrices\Service\ConfigService;
use Psr\Log\LoggerInterface;
use Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Service\ProductServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\Product;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Registers our custom plugin views.
 *
 * @package MaxiaAdvBlockPrices\Subscriber
 */
class Frontend implements SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Detail' => ['onPostDispatchDetail', 600],
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Checkout' => 'onPostDispatchCheckout',
        ];
    }

    /**
     * @var ContainerInterface $container
     */
    private $container;

    /**
     * @var ConfigService $config
     */
    private $config;

    /**
     * @var StructConverter $structConverter
     */
    private $structConverter;

    /**
     * @var ProductServiceInterface $productService
     */
    private $productService;

    /**
     * @var ContextServiceInterface $contextService
     */
    private $contextService;

    /**
     * @var LoggerInterface
     */
    private $log;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->config = $this->container->get('maxia.adv_block_prices.config_service');
        $this->structConverter = $this->container->get('maxia.adv_block_prices.struct_converter');
        $this->productService = $this->container->get('shopware_storefront.product_service');
        $this->contextService = $this->container->get('shopware_storefront.context_service');
        $this->log = $this->container->get('pluginlogger');
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     * @return mixed|void
     */
    public function onPostDispatchDetail(\Enlight_Event_EventArgs $args)
    {
        /** @var \Shopware_Controllers_Frontend_Detail $controller */
        $controller = $args->getSubject();
        $view = $controller->View();
        $vars = $view->getAssign();
        $sArticle = $vars['sArticle'];

        $view->addTemplateDir(realpath(__DIR__ . '/../Resources/views'));

        if (!$sArticle) {
            return;
        }

        try {
            $config = $this->config->getConfig();
            $product = $this->productService->get($sArticle['ordernumber'], $this->contextService->getShopContext());

            if (empty($product)) {
                // product not available
                return;
            }

            $hasLiveShopping = $product->hasAttribute('live_shopping')
                && $product->getAttribute('live_shopping')->get('has_live_shopping');

            if ($hasLiveShopping) {
                // deactivate plugin for liveshopping articles
                $sArticle['sBlockPrices'] = [];
                $config['totalDisplayVisible'] = false;

            } else {
                // check if total display should be visible
                $sArticle =  $this->structConverter->convertProduct($sArticle, $product);

                if ($config['pluginEnabled'] && $config['showTotal'] && $config['showTotalBlockPricesOnly']) {

                    $config['totalDisplayVisible'] = isset($sArticle['sBlockPrices']) && count($sArticle['sBlockPrices']);

                } else {
                    $config['totalDisplayVisible'] = $config['pluginEnabled'] && $config['showTotal'];
                }
            }

            $view->assign('mabpConfig', $config);
            $view->assign('sArticle', $sArticle);

        } catch (\Exception $e) {

            $view->assign('mabpConfig', false);
            $this->log->error($e->getMessage(), ['exception' => $e]);
            return $args->getReturn();
        }
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     */
    public function onPostDispatchCheckout(\Enlight_Event_EventArgs $args)
    {
        /** @var \Shopware_Controllers_Frontend_Detail $controller */
        $controller = $args->getSubject();
        $view = $controller->View();

        $view->assign('mabpConfig', $this->config->getConfig());
        $view->addTemplateDir(realpath(__DIR__ . '/../Resources/views'));
    }
}