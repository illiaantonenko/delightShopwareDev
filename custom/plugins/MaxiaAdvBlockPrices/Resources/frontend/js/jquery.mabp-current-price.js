;(function($) {
    'use strict';

    /**
     * Displays the current price, updates on price change
     */
    $.plugin('mabpCurrentPrice', {

        defaults: {
            quantitySelector: '.product--details #sQuantity',
            customProductsCheckSelector: '.custom-products--form',
            swkeProductSetsCheckSelector: '.buybox--article-set',
            priceSelector: '.mabp-price',
            priceContainerSelector: '.product--price',
            pseudoPriceSelector: '.mabp-pseudoprice',
            pseudoPricePercentSelector: '.mabp-pseudoprice-percent',
            pseudoPriceContainerSelector: '.mabp-pseudoprice-container',
            discountClass: 'price--discount',
            locale: 'de-DE',
            symbol: '€',
            price: 1,
            pseudoprice: null
        },

        price: null,
        pseudoprice: null,
        hasCustomProducts: false,
        hasSwkeProductSets: false,

        /**
         * Constructor
         */
        init: function () {
            var me = this;

            this.applyDataAttributes(true, []);

            this.price = this.opts.price;
            this.pseudoprice = this.opts.pseudoprice;

            this.$price = this.$el.find(this.opts.priceSelector);
            this.$priceContainer = this.$el.find(this.opts.priceContainerSelector);
            this.$pseudoPrice = this.$el.find(this.opts.pseudoPriceSelector);
            this.$pseudoPricePercent = this.$el.find(this.opts.pseudoPricePercentSelector);
            this.$pseudoPriceContainer = this.$el.find(this.opts.pseudoPriceContainerSelector);

            this.registerEvents();
        },

        /**
         * Registers required events
         */
        registerEvents: function () {
            var me = this;

            this.hasCustomProducts = $(this.opts.customProductsCheckSelector).length > 0;
            this.hasSwkeProductSets = $(this.opts.swkeProductSetsCheckSelector).length > 0;

            if (this.hasCustomProducts) {
                $.subscribe(me.getEventName('plugin/OptionManager/onOverviewPanelSuccess'), $.proxy(me.onCustomProductsChange, me));

            } else if (this.hasSwkeProductSets) {
                $.subscribe(me.getEventName('plugin/swkweSetBuy/afterUpdatePrice'), $.proxy(me._onSwkeProductSetsChange, me));

            } else {
                $.subscribe(me.getEventName('plugin/mabpBlockPrices/onPriceChange'), $.proxy(me.onPriceChange, me));
            }
        },

        /**
         * Called when a new price has been selected in the table.
         *
         * @param event
         * @param plugin
         * @param group
         */
        onPriceChange: function (event, plugin, group) {
            this.price = group.price;
            this.pseudoprice = group.pseudoprice;
            this.referenceprice = group.referenceprice;
            this.update();
        },

        /**
         * Called on SwkeProductSets change
         * @param event
         * @param plugin
         * @param price
         * @private
         */
        _onSwkeProductSetsChange: function (event, plugin, price) {
            this.price = price;
            this.pseudoprice = null;
            this.referenceprice = null;
            this.update();
        },

        /**
         * Updates the view
         */
        update: function () {
            this.$price.html(
                this.price.toLocaleString(this.opts.locale, { minimumFractionDigits: 2 }) + ' ' + this.opts.symbol
            );

            if (this.pseudoprice !== null && this.pseudoprice > 0 && this.pseudoprice !== this.price) {

                this.$pseudoPrice.html(
                    this.pseudoprice.toLocaleString(this.opts.locale, { minimumFractionDigits: 2 }) + ' ' + this.opts.symbol
                );

                var savingsPercent = Number((this.pseudoprice - this.price) * 100 / this.pseudoprice)
                    .toLocaleString(this.opts.locale, { maximumFractionDigits: 1 });

                this.$pseudoPricePercent.html(savingsPercent);

                this.$pseudoPriceContainer.show();
                this.$priceContainer.addClass(this.opts.discountClass);

            } else {
                this.$pseudoPriceContainer.hide();
                this.$priceContainer.removeClass(this.opts.discountClass);
            }

            $.publish('plugin/mabpCurrentPrice/onPriceChange', [ this, this.price, this.pseudoprice ]);
        },

        /**
         * Destructor
         */
        destroy: function () {
            var me = this;

            $.unsubscribe(me.getEventName('plugin/mabpBlockPrices/onPriceChange'));
            $.unsubscribe(me.getEventName('plugin/swkweSetBuy/afterUpdatePrice'));
            $.unsubscribe(me.getEventName('plugin/OptionManager/onOverviewPanelSuccess'));

            me._destroy();
        }
    });

})(jQuery);

jQuery(document).ready(function() {
    StateManager.addPlugin('.product--details [data-mabp-current-price="true"]', 'mabpCurrentPrice');
});
