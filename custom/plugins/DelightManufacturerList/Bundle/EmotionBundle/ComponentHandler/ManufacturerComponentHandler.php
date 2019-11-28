<?php


namespace DelightManufacturerList\Bundle\EmotionBundle\ComponentHandler;


use Shopware\Bundle\EmotionBundle\ComponentHandler\ComponentHandlerInterface;
use Shopware\Bundle\EmotionBundle\Struct\Collection\PrepareDataCollection;
use Shopware\Bundle\EmotionBundle\Struct\Collection\ResolvedDataCollection;
use Shopware\Bundle\EmotionBundle\Struct\Element;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;
use Shopware\Models\Article\Supplier;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ManufacturerComponentHandler implements ComponentHandlerInterface
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
        return $element->getComponent()->getTemplate() === 'emotion_manufacturer';
    }

    public function prepare(PrepareDataCollection $collection, Element $element, ShopContextInterface $context)
    {

    }

    public function handle(ResolvedDataCollection $collection, Element $element, ShopContextInterface $context)
    {
        $supplierRepository = $this->container->get('models')->getRepository(Supplier::class);

        /** @var Supplier $supplierData */
        $supplierData = $supplierRepository->findAll();

        /** @var Supplier $item */
        $arr = [];
        foreach ($supplierData as $item) {
            $name = $item->getName();
            $key = strtoupper(substr($name, 0, 1));
            $arr [$key] [] = ['name' => $name, 'link' => $item->getLink()];
            natsort($arr [$key]);
        }
        ksort($arr);
        $element->getData()->set('manufacturers', $arr);
    }
}