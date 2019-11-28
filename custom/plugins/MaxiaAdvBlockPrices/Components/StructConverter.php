<?php

namespace MaxiaAdvBlockPrices\Components;

use Enlight_Event_EventManager;
use MaxiaAdvBlockPrices\Service\ConfigService;
use Shopware\Bundle\StoreFrontBundle\Struct\Product;
use Shopware\Bundle\StoreFrontBundle\Struct\Product\Price;
use Shopware\Components\Compatibility\LegacyStructConverter;

class StructConverter
{
    /**
     * @var ConfigService $config
     */
    protected $config;

    /**
     * @var LegacyStructConverter $legacyStructConverter
     */
    protected $legacyStructConverter;

    /**
     * @var Enlight_Event_EventManager $eventManager
     */
    private $eventManager;

    /**
     * StructConverter constructor.
     *
     * @param ConfigService $config
     * @param LegacyStructConverter $legacyStructConverter
     * @param Enlight_Event_EventManager $eventManager
     */
    public function __construct(
        ConfigService $config,
        LegacyStructConverter $legacyStructConverter,
        Enlight_Event_EventManager $eventManager
    ) {
        $this->config = $config;
        $this->legacyStructConverter = $legacyStructConverter;
        $this->eventManager = $eventManager;
    }

    /**
     * Updates sBlockPrices according to configuration
     *
     * - Filters unavailable prices
     * - Adds savings to each row
     *
     * @param $sArticle
     * @param Product $product
     * @return array
     * @throws \Enlight_Event_Exception
     */
    public function convertProduct($sArticle, Product $product)
    {
        if (empty($sArticle['sBlockPrices'])) {
            return $sArticle;

        } elseif (count($sArticle['sBlockPrices']) === 1) {
            unset($sArticle['sBlockPrices']);
            return $sArticle;
        }

        // filter unavailable prices
        foreach ($sArticle['sBlockPrices'] as $index => &$row) {
            $isInStock = !$sArticle['laststock'] || $row['from'] <= $sArticle['instock'];
            $exceedsMaxPurchase = $sArticle['maxpurchase'] && $row['from'] > $sArticle['maxpurchase'];
            $exceedsMinPurchase = $sArticle['minpurchase'] && $row['from'] < $sArticle['minpurchase'];

            $row['isAvailable'] = $isInStock && !$exceedsMaxPurchase && !$exceedsMinPurchase;

            if ($row['isAvailable'] && !isset($sArticle['firstAvailablePrice'])) {
                $sArticle['firstAvailablePrice'] = $row;
            }

            if (!$row['isAvailable'] && $this->config->get('hideUnavailable', false)) {
                unset($sArticle['sBlockPrices'][$index]);
            }
        }

        // display as main price if only one price is available
        if (count($sArticle['sBlockPrices']) <= 1) {
            unset($sArticle['sBlockPrices']);

            $sArticle = array_merge(
                $sArticle,
                $this->legacyStructConverter->convertPriceStruct($this->getFirstAvailablePrice($product))
            );

        } else {
            $sArticle['sBlockPrices'] = array_values($sArticle['sBlockPrices']);

            // add savings
            foreach ($sArticle['sBlockPrices'] as &$row) {
                $row = array_merge($row, $this->getSavings($sArticle, $row));
            }

            // reverse order
            if ($this->config->get('sortOrder') === 'desc') {
                $sArticle['sBlockPrices'] = array_reverse($sArticle['sBlockPrices']);
            }
        }

        return $this->eventManager->filter('Maxia_AdvBlockPrices_UpdateArticle', $sArticle, [
            'product' => $product
        ]);
    }

    /**
     * Returns the first available price (respecting min/max purchase, instock) of a product
     *
     * @param Product $product
     * @return Price
     */
    protected function getFirstAvailablePrice(Product $product)
    {
        $foundPrice = null;
        foreach ($product->getPrices() as $price) {

            if ($product->isCloseouts()) {

                if ($price->getFrom() > $product->getStock())
                    continue;
            }

            if ($product->getUnit()) {

                $max = $product->getUnit()->getMaxPurchase();
                $min = $product->getUnit()->getMinPurchase();

                if ($min && $price->getFrom() < $min)
                    continue;
                if ($max && $price->getFrom() > $max)
                    continue;
            }

            $foundPrice = $price;
            break;
        }

        return $foundPrice ? $foundPrice : $product->getPrices()[0];
    }

    /**
     * Returns savings based on first block price for a row
     *
     * @param $sArticle
     * @param $row
     * @return array
     * @throws \Enlight_Event_Exception
     */
    public function getSavings($sArticle, $row)
    {
        // add savings
        $firstPrice = $sArticle['sBlockPrices'][0]['price'];

        $savingsAbsolute = $firstPrice - $row['price'];
        $savingsPercent = round($savingsAbsolute * 100 / $firstPrice, $this->config->get('percentDecimals'));

        $result = [
            'savingsAbsolute' => $savingsAbsolute,
            'savingsPercent' => $savingsPercent,
        ];

        return $this->eventManager->filter('Maxia_AdvBlockPrices_GetSavings', $result, [
            'row' => $row,
            'sArticle' => $sArticle
        ]);
    }
}