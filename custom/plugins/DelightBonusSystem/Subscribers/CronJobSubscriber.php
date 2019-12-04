<?php

namespace RevanCronJobs\Subscribers;

use Shopware\Models\Article\Article;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CronJobSubscriber implements \Enlight\Event\SubscriberInterface
{

    private $pluginDirectory;
    private $container;

    public function __construct($pluginDirectory, ContainerInterface $container)
    {
        $this->pluginDirectory = $pluginDirectory;
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Shopware_CronJob_ExportProducts' => 'onExportProductsCronJob',
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_CronJob' => 'postDispatchRegistration'
        ];
    }

    public function onExportProductsCronJob(\Shopware_Components_Cron_CronJob $job)
    {
        /** @var  $controller */
//        $controller = $args->getSubject();
        $models = $this->container->get('models');
        $articles = $models->getRepository(Article::class)->findAll();
        $dir = '/../../../exportedFiles';
        /** @var Article $article */
        if (!file_exists($this->pluginDirectory . '/../../../ITDelight')) {
            mkdir($this->pluginDirectory . '/../../../ITDelight', 0755, true);
            if (!file_exists($this->pluginDirectory . '/../../../ITDelight/exportedFiles')) {
                mkdir($this->pluginDirectory . '/../../../ITDelight/exportedFiles', 0755, true);
            }
        }
        $fp = fopen($this->pluginDirectory . '/../../../ITDelight/exportedFiles' . '/text_' . date('d_m_Y:H_i_s') . '.csv', 'w');
        foreach ($articles as $article) {
            if ($article->getMainDetail()->getAttribute()->getIsExported()) {
                fputcsv($fp, [
                    'id' => $article->getId(),
                    'name' => $article->getName(),
                    'active' => $article->getActive(),
                    'description' => $article->getDescription()
                ]);
            }
        }
        fclose($fp);
    }

}