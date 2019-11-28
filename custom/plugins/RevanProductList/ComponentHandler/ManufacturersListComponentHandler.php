<?php


namespace RevanProductList\ComponentHandler;

use Shopware\Bundle\EmotionBundle\ComponentHandler\ComponentHandlerInterface;
use Shopware\Bundle\EmotionBundle\Struct\Collection\PrepareDataCollection;
use Shopware\Bundle\EmotionBundle\Struct\Collection\ResolvedDataCollection;
use Shopware\Bundle\EmotionBundle\Struct\Element;

use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Article\Supplier;
use Shopware\Models\Article\SupplierRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ManufacturersListComponentHandler implements ComponentHandlerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $containerInterface)
    {
        $this->container = $containerInterface;
    }

    /**
     * @return bool
     */
    public function supports(Element $element)
    {
        return $element->getComponent()->getTemplate() === 'emotion_manufacturers';
    }

    public function prepare(PrepareDataCollection $collection, Element $element, ShopContextInterface $context)
    {
        // TODO: Implement prepare() method.
    }

    public function handle(ResolvedDataCollection $collection, Element $element, ShopContextInterface $context)
    {

        error_log(__METHOD__);
        /** @var SupplierRepository $supplierRepository */
        $supplierRepository = $this->container->get('models')->getRepository(Supplier::class);

        /** @var Supplier $supplierData */
        $supplierData = $supplierRepository->findAll();

        /** @var Supplier $supplierDatum */
        $arr = [];
        foreach ($supplierData as $supplierDatum) {
            $arr [] = $supplierDatum->getName();
        }
        $element->getData()->set('manufacturers', $arr);
    }
}