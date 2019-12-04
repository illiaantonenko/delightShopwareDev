<?php

namespace RevanAddManufacturerToList\Subscribers;

use Enlight_Event_EventArgs;
use Shopware\Components\Plugin\ConfigReader;

class FrontendDetailSubscriber implements \Enlight\Event\SubscriberInterface
{

    private $pluginDirectory;

    public function __construct($pluginDirectory)
    {
        $this->pluginDirectory = $pluginDirectory;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Detail' => 'onFrontendDetail'
        ];
    }

    public function onFrontendDetail(\Enlight_Controller_ActionEventArgs $args)
    {
        /** @var  $controller */
        $controller = $args->getSubject();
        $view = $controller->View();
        $view->addTemplateDir($this->pluginDirectory . '/Resources/views');
    }
}