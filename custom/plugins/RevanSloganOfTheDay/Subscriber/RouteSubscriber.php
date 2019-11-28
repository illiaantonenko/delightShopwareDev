<?php


namespace RevanSloganOfTheDay\Subscriber;

use Enlight\Event\SubscriberInterface;
use Shopware\Components\Plugin\ConfigReader;
use RevanSloganOfTheDay\Components\SloganPrinter;

class RouteSubscriber implements SubscriberInterface
{
    private $pluginDirectory;
    private $sloganPrinter;
    private $config;

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onPostDispatch'
        ];
    }

    public function __construct($pluginName, $pluginDirectory, SloganPrinter $sloganPrinter, ConfigReader $configReader)
    {
        $this->pluginDirectory = $pluginDirectory;
        $this->sloganPrinter = $sloganPrinter;

        $this->config = $configReader->getByPluginName($pluginName);
    }

    public function onPostDispatch(\Enlight_Controller_ActionEventArgs $args)
    {
        /** @var \Enlight_Controller_Action $controller */
        $controller = $args->get('subject');
        $view = $controller->View();

        $view->addTemplateDir($this->pluginDirectory . '/Resources/views');

        $view->assign('revanSloganFrontSize', $this->config['revanSloganFrontSize']);
        $view->assign('revanSloganItalic', $this->config['revanSloganItalic']);
        $view->assign('revanSloganContent', $this->config['revanSloganContent']);

        if (!$this->config['revanSloganContent']) {
            $view->assign('revanSloganContent', $this->sloganPrinter->getSlogan());
        }
    }
}
