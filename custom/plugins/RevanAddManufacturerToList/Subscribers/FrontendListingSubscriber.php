<?php

namespace RevanAddManufacturerToList\Subscribers;

use Enlight_Event_EventArgs;
use Shopware\Components\Plugin\ConfigReader;
use Shopware\Models\Article\Article;

class FrontendListingSubscriber implements \Enlight\Event\SubscriberInterface
{

    private $pluginDirectory;

    public function __construct($pluginDirectory)
    {
        $this->pluginDirectory = $pluginDirectory;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Listing' => 'onFrontendListing'
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
}