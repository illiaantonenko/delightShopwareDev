<?php

namespace DelightAddWeight\Subscriber;

use Enlight\Event\SubscriberInterface;

class FrontendDetailSubscriber implements SubscriberInterface
{
    private $pluginDir;

    public function __construct($pluginDir)
    {
        $this->pluginDir = $pluginDir;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Detail' => ['onFrontendDetail', PHP_INT_MAX]
        ];
    }

    public function onFrontendDetail(\Enlight_Controller_ActionEventArgs $args)
    {
        $subject = $args->getSubject();
//        error_log(print_r($this->pluginDir, true));
        $subject->View()->addTemplateDir($this->pluginDir . '/Resources/views');
        $subject->View()->extendsBlock(
            'plugins_maxia_adv_block_prices_current_price_tax',
            PHP_EOL . '{if $sArticle.weight}
                                    Weight: {$sArticle.weight}
                                {/if}',
            'prepend'
        );

    }
}