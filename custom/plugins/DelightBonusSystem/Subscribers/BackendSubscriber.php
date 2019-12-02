<?php

namespace DelightBonusSystem\Subscribers;

use Enlight_Controller_Request_RequestHttp;
use Shopware\Models\Order\Order;

class FrontendSubscriber implements \Enlight\Event\SubscriberInterface
{

    private $pluginDirectory;

    public function __construct($pluginDirectory)
    {
        $this->pluginDirectory = $pluginDirectory;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Listing' => ['onFrontendListing', 600],
            'Enlight_Controller_Action_PostDispatch_Backend_Order' => 'onDispatchCheckout',
            'Shopware\Models\Order\Order::preUpdate',
            'Shopware\Models\Order\Order::postUpdate',
        ];
    }

    public function onFrontendListing(\Enlight_Controller_ActionEventArgs $args)
    {
        /** @var  $controller */
        $controller = $args->getSubject();
        $view = $controller->View();
        $view->addTemplateDir($this->pluginDirectory . '/Resources/views');
//           $view->assign('manufacturer',$article['supplierName']);
//        error_log(print_r($categoryContent, true));
//           die(print_r(get_class_vars($controller)));
    }

    public function onDispatchCheckout(\Enlight_Controller_ActionEventArgs $args)
    {
        /** @var $request Enlight_Controller_Request_RequestHttp */
        $request = $args->getSubject()->Request()->getParams();
        if ($request['controller'] === 'Order' and $request['action'] === 'save') {
            $manager = $args->getSubject()->getModelManager();
            /** @var $order Order */

            $order = $manager->getRepository(Order::class)->find($request['id']);
            $status = $order->getOrderStatus();
//
            error_log(print_r([$request['status'], [$status->getName(), $status->get]], 1));
        }
//        die('<pre>'.print_r($log,1).'</pre>');

    }
}