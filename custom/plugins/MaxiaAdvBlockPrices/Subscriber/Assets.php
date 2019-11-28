<?php

namespace MaxiaAdvBlockPrices\Subscriber;

use Enlight\Event\SubscriberInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Shopware\Components\Plugin\ConfigReader;
use Shopware\Components\Theme\LessDefinition;

class Assets implements SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'Theme_Compiler_Collect_Plugin_Less' => 'onCollectLessFiles'
        ];
    }

    /** @var ConfigReader */
    private $configReader;

    /**
     * AssetSubscriber constructor.
     * @param ConfigReader $configReader
     */
    public function __construct(ConfigReader $configReader)
    {
        $this->configReader = $configReader;
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     * @return ArrayCollection
     * @throws \Enlight_Exception
     */
    public function onCollectLessFiles(\Enlight_Event_EventArgs $args)
    {
        $shop = $args->getShop();
        $config = $this->configReader->getByPluginName('MaxiaAdvBlockPrices', $shop);

        $vars = [
            'mabp-row-height' => $config['stylesRowHeight'],
            'mabp-row-height-active' => $config['stylesRowHeightActive'],
            'mabp-price-font-size' => $config['stylesPriceFontSize'],
            'mabp-price-font-size-active' => $config['stylesPriceFontSizeActive'],
            'mabp-discount-badge-bg-color' => $config['stylesBadgeBgColor'],
            'mabp-discount-badge-color' => $config['stylesBadgeColor'],
            'mabp-active-row-indicator-color' => $config['stylesActiveRowIndicatorColor'],
            'mabp-active-row-indicator-width' => $config['stylesActiveRowIndicatorWidth'],
            'mabp-border-radius' => $config['stylesBorderRadius'] . 'px',
            'mabp-border-color' => $config['stylesBorderColor'],
            'mabp-priceinfo-font-size' => $config['stylesFontSizePriceInfo'],
            'mabp-amount-font-size' => $config['stylesFontSizeAmount'],
            'mabp-total-display-font-size' => $config['stylesFontSizeSum'],
            'mabp-total-display-savings-font-size' => $config['stylesFontSizeSumSavings'],
            'mabp-max-height' => $config['stylesMaxHeight'],
            'mabp-unavailable-row-bg' => $config['stylesRowBackgroundNotAvailable'],
            'mabp-unavailable-row-opacity' => $config['stylesRowOpacityNotAvailable'],
            'mabp-popup-theme' => $config['stylesPopupTheme'],
            'mabp-popup-font-size' => $config['stylesPopupFontSize'],
            'mabp-popup-savings-font-size' => $config['stylesPopupSavingsFontSize'],
            'mabp-popup-color' => $config['stylesPopupColorLight'],
            'mabp-popup-dark-color' => $config['stylesPopupColorDark'],
            'mabp-number-input-minimal-color' => $config['stylesQuantityMinimalColor'],
            'mabp-number-input-minimal-border-color' => $config['stylesQuantityMinimalBorderColor'],
        ];

        foreach ($vars as $key => $value) {
            if (empty($value)) {
                unset($vars[$key]);
            }
        }

        $less = new LessDefinition(
            $vars,
            [ __DIR__ . '/..//Resources/views/frontend/_public/src/less/all.less' ],
            realpath(__DIR__ . '/../')
        );

        return new ArrayCollection([$less]);
    }
}