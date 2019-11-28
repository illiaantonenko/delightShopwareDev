<?php

use Shopware\Components\Model\ModelManager;
use Shopware\Models\Article\Article as ArticleAlias;

class Shopware_Controllers_Frontend_CronJob extends Enlight_Controller_Action
{
    public function indexAction()
    {
//        $pluginPath = $this->container->getParameter('revan_cron_jobs.plugin_dir');
//        $this->container->get('template')->addTemplateDir($pluginPath . '/Resources/views/');
//        $this->container->get('snippets')->addConfigDir($pluginPath . '/Resources/snippets/');


        /** @var ModelManager $models */
        $models = $this->container->get('models');
        $articles = $models->getRepository(ArticleAlias::class)->findAll();
        /** @var ArticleAlias $article */
        $arr =[];
        foreach ($articles as $article)
        {

            $arr [] =$article->getMainDetail()->getAttribute()->getIsExported();
        }
//        echo "<pre>" . print_r($arr) . "</pre>";
//        die;
//
//
        $builder = $this->get('models')->createQueryBuilder();
        $sql = $builder->select(['attribute.isExported'])->from(ArticleAlias::class, 'product')
            ->leftJoin('product.mainDetail', 'details')
            ->leftJoin('details.attribute', 'attribute')
            ->getQuery()
            ->execute();
//        echo "<pre>" . print_r($sql) . "</pre>";
//        die;
//        $this->View()->assign('collection', $builder->getQuery()->getArrayResult());

    }
}