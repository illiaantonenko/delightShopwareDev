<?php


namespace DelightBonusSystem;


use Shopware\Bundle\AttributeBundle\Service\CrudService;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;

class DelightBonusSystem extends Plugin
{
    /**
     * {@inheritdoc}
     */
    public function install(InstallContext $installContext)
    {
        /**@var $service CrudService */
        $service = $this->container->get('shopware_attribute.crud_service');
        $service->update('s_articles_attributes', 'cost_in_token', 'integer', [
            'label' => 'Cost in tokens',
            'supportText' => 'Product cost in tokens',
            'displayInBackend' => true,
//            'custom' => true
        ],
            null, false, 0
        );
        $service->update('s_articles_attributes', 'token_for_purchase', 'integer', [
            'label' => 'Tokens for purchase',
            'supportText' => 'Bonus tokens for user after purchase',
            'displayInBackend' => true,
//            'custom' => true
        ],
            null, false, 0
        );
        $service->update('s_articles_attributes', 'in_bonus_program', 'boolean', [
            'label' => 'In bonus program',
            'supportText' => 'Product participates in the bonus program',
            'displayInBackend' => true,
//            'custom' => true
        ],
            null, false, 0
        );
        $service->update('s_user_attributes', 'tokens', 'integer', [
            'label' => 'Tokens',
            'supportText' => 'Sum of user\'s bonus tokens',
            'displayInBackend' => true,
        ]);
        parent::install($installContext);
    }

    /**
     * {@inheritdoc}
     */
    public function activate(ActivateContext $activateContext)
    {
        $activateContext->scheduleClearCache(InstallContext::CACHE_LIST_ALL);
    }

    public function uninstall(UninstallContext $uninstallContext)
    {
        $service = $this->container->get('shopware_attribute.crud_service');
        $service->delete('s_articles_attributes', 'cost_in_token');
        $service->delete('s_articles_attributes', 'token_for_purchase');
        $service->delete('s_articles_attributes', 'in_bonus_program');
        $service->delete('s_user_attributes', 'tokens');
//        if (!$uninstallContext->keepUserData()) {
//            $this->removeDatabase();
//        }
    }

//    private function createDatabase()
//    {
//        /* @var $modelManager ModelManager */
//        $modelManager = $this->container->get('models');
//        $tool = new SchemaTool($modelManager);
//
//        $classes = $this->getClasses($modelManager);
//
//        $tool->updateSchema($classes, true); // make sure to use the save mode
//    }
//
//    private function removeDatabase()
//    {
//        /* @var $modelManager ModelManager */
//        $modelManager = $this->container->get('models');
//        $tool = new SchemaTool($modelManager);
//
//        $classes = $this->getClasses($modelManager);
//
//        $tool->dropSchema($classes);
//    }
//
//    /**
//     * @param ModelManager $modelManager
//     * @return array
//     */
//    private function getClasses(ModelManager $modelManager)
//    {
//        return [
//            $modelManager->getClassMetadata(Callback::class)
//        ];
//    }
//
//    public static function getSubscribedEvents()
//    {
//        return [
//            'Enlight_Controller_Action_PostDispatchSecure_Backend_DelightCallback' => 'postDispatchRegistration',
//            'Enlight_Controller_Action_PostDispatchSecure_Backend' => 'onPostDispatchBackend',
//        ];
//    }
//
//    public function onPostDispatchBackend(\Enlight_Event_EventArgs $args)
//    {
//        $args->getSubject()->View()->addTemplateDir(__DIR__.'/Resources/views');
//    }
//    public function postDispatchRegistration(\Enlight_Event_EventArgs $args)
//    {
//        /* @var Shopware_Controllers_Backend_DelightCallback $subject /
//         */
//        $subject = $args->getSubject();
//            $subject->View()->extendsTemplate('backend/delight_callback/app.js');
//    }
}