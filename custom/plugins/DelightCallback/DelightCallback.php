<?php


namespace DelightCallback;


use DelightCallback\Models\Callback;
use Doctrine\ORM\Tools\SchemaTool;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware_Controllers_Backend_DelightCallback;

class DelightCallback extends Plugin
{
    /**
     * {@inheritdoc}
     */
    public function install(InstallContext $installContext)
    {
        $this->createDatabase();
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
        if (!$uninstallContext->keepUserData()) {
            $this->removeDatabase();
        }
    }

    private function createDatabase()
    {
        /* @var $modelManager ModelManager */
        $modelManager = $this->container->get('models');
        $tool = new SchemaTool($modelManager);

        $classes = $this->getClasses($modelManager);

        $tool->updateSchema($classes, true); // make sure to use the save mode
    }

    private function removeDatabase()
    {
        /* @var $modelManager ModelManager */
        $modelManager = $this->container->get('models');
        $tool = new SchemaTool($modelManager);

        $classes = $this->getClasses($modelManager);

        $tool->dropSchema($classes);
    }

    /**
     * @param ModelManager $modelManager
     * @return array
     */
    private function getClasses(ModelManager $modelManager)
    {
        return [
            $modelManager->getClassMetadata(Callback::class)
        ];
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Backend_DelightCallback' => 'postDispatchRegistration',
            'Enlight_Controller_Action_PostDispatchSecure_Backend' => 'onPostDispatchBackend',
        ];
    }

    public function onPostDispatchBackend(\Enlight_Event_EventArgs $args)
    {
        $args->getSubject()->View()->addTemplateDir(__DIR__.'/Resources/views');
    }
    public function postDispatchRegistration(\Enlight_Event_EventArgs $args)
    {
        /* @var Shopware_Controllers_Backend_DelightCallback $subject /
         */
        $subject = $args->getSubject();
            $subject->View()->extendsTemplate('backend/delight_callback/app.js');
    }
}