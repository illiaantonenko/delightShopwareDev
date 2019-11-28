<?php


use Shopware\Components\Model\ModelManager;
use Shopware\Models\Article\Article;
use Shopware\Models\Article\Detail;

class Shopware_Controllers_Frontend_ProductList extends Enlight_Controller_Action
{
    public function indexAction()
    {
        /** @var $modelManager ModelManager */
        $modelManager = $this->container->get('models');
//        $items = $modelManager->getRepository(Detail::class)->createQueryBuilder('detail')
//            ->addSelect(['main'])
//            ->leftJoin('detail.article', 'main')->getQuery()->getArrayResult();
        $items = $modelManager->getRepository(Article::class)->createQueryBuilder('article')->setMaxResults(10)->setFirstResult(0)
            ->addSelect('details')
            ->leftJoin('article.details', 'details')->getQuery()->getArrayResult();
//        echo "<pre>" . print_r($items, true) . "</pre>";
//        die;
        $this->View()->assign('items', $items);

    }

    public function loadAction()
    {
        $this->Request()->setHeader('Content-Type', 'application/json');
        $this->Front()->Plugins()->ViewRenderer()->setNoRender();

        $offset = $this->Request()->getPost('offset');
        /** @var $modelManager ModelManager */
        $modelManager = $this->container->get('models');
        $items = $modelManager->getRepository(Article::class)->createQueryBuilder('article')->setMaxResults(10)->setFirstResult($offset)
            ->addSelect('details')
            ->leftJoin('article.details', 'details')->getQuery()->getArrayResult();


        $this->Response()->setContent(json_encode(
            [
                'items' => $items,
                'offset' => $offset,
            ]
        ));
    }
}