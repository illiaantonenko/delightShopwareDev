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
        ],
            null, false, 0
        );
        $service->update('s_order_attributes', 'tokens_accrued', 'boolean', [
            'label' => 'Is tokens accrued',
            'supportText' => 'Is tokens accrued to user after order',
            'displayInBackend' => true,
        ],
            null, false, 0
        );
        $service->update('s_order_attributes', 'tokens_sum', 'integer', [
            'label' => 'Tokens sum',
            'supportText' => 'Tokens sum for user\'s accrual',
            'displayInBackend' => true,
        ],
            null, false, 0
        );
        $metaDataCache = $this->container->get('models')->getConfiguration()->getMetadataCacheImpl();
        $metaDataCache->deleteAll();
        $this->container->get('models')->generateAttributeModels(['s_user_attributes']);
        $this->container->get('models')->generateAttributeModels(['s_order_attributes']);
        $this->container->get('models')->generateAttributeModels(['s_articles_attributes']);
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
        $service->delete('s_order_attributes', 'tokens_accrued');
        $service->delete('s_order_attributes', 'tokens_sum');
//        if (!$uninstallContext->keepUserData()) {
//            $this->removeDatabase();
//        }
    }

}