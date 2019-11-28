;(function($) {
    'use strict';

    /**
     * Display the total price, updates on quantity change.
     */
    $.plugin('mabpTotalDisplay', {

        defaults: {
            tableSelector: '.mabp-block-prices--container',
            quantitySelector: '.product--details #sQuantity',
            sumValueSelector: '.mabp-total-display--sum-value',
            savingsSelector: '.mabp-total-display--savings',
            savingsValueSelector: '.mabp-total-display--savings-value',
            customProductsCheckSelector: '.custom-products--form',
            swkeProductSetsCheckSelector: '.buybox--article-set',
            initialPrice: 1,
            initialQuantity: 1,
            locale: 'de-DE',
            showPopup: false,
        },

        total: null,
        totalSavings: 0,
        price: null,
        quantity: 1,
        hasCustomProducts: false,
        hasSwkeProductSets: false,
        popper: null,
        popperConfig: {
            placement: 'bottom',
            modifiers: {
                offset: {
                    enabled: true,
                    offset: '0,10'
                },
                flip: {
                    enabled: false
                }
            }
        },

        /**
         * Constructor
         */
        init: function () {
            var me = this;

            this.applyDataAttributes(true, []);

            this.$table = $(this.opts.tableSelector).first();
            this.$quantitySelect = $(this.opts.quantitySelector).first();
            this.$sum = this.$el.find(this.opts.sumValueSelector).first();
            this.$savings = this.$el.find(this.opts.savingsSelector).first();
            this.$savingsValue = this.$el.find(this.opts.savingsValueSelector).first();
            this.price = parseFloat(this.opts.initialPrice);
            this.total = this.opts.initialQuantity * this.price;

            if (this.opts.initialQuantity) {
                this.quantity = this.opts.initialQuantity;
            }

            if (this.opts.showPopup) {
                if (!this.$quantitySelect.length || this.$quantitySelect.is(':hidden')) {
                    this.destroy();
                    return;
                }

                this.popperConfig.placement = this.$el.attr('x-placement');
                this.popper = new Popper(this.$quantitySelect.get(0), this.$el.get(0), this.popperConfig);
            }

            this.registerEvents();
            this.update();

            this.$el.addClass('js--rendered');
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

                me.$quantitySelect.bind('change', function() {
                    me.quantity = me.$quantitySelect.val();
                });
            }
            else if (this.hasSwkeProductSets) {
                $.subscribe(me.getEventName('plugin/swkweSetBuy/afterUpdatePrice'), $.proxy(me._onSwkeProductSetsChange, me));

                me.$quantitySelect.bind('change', function() {
                    me.quantity = me.$quantitySelect.val();
                });
            }
            else {
                $.subscribe(me.getEventName('plugin/mabpBlockPrices/onPriceChange'), $.proxy(me.onPriceChange, me));

                me.$quantitySelect.bind('change', function(event) {
                    me.onQuantityChange.call(me, event, $(this).val());
                });
            }

            if (me.opts.showPopup) {
                $.subscribe(me.getEventName('plugin/mabpNumberInput/onShowPopup'), $.proxy(me._onMinMaxPopup, me));
            }
        },

        /**
         * Called when a new price has been selected in the table.
         *
         * @param event
         * @param plugin
         * @param group
         * @param quantity
         */
        onPriceChange: function (event, plugin, group, quantity) {
            var me = this;

            if (this.$table.length && this.$table.get(0).isSameNode( plugin.$el.get(0) )) {

                this.price = group.price;
                this.quantity = quantity;
                this.total = this.price * this.quantity;
                this.update();
            }
        },

        /**
         * Called when the #sQuantity field changes.
         * @param event
         * @param quantity
         */
        onQuantityChange: function (event, quantity) {
            this.quantity = quantity;
            this.total = this.price * quantity;
            this.update();
        },

        /**
         * Called on SwagCustomProducts change
         *
         * @param event
         * @param plugin
         * @param args
         */
        onCustomProductsChange: function (event, plugin, args) {
            this.total = args.data.total;
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
            this.total = price * this.quantity;
            this.update();
        },

        /**
         * Hides sum popup when min/max purchase popup was shown
         *
         * @param event
         * @param plugin
         * @param $popup
         * @param lastValue
         * @param $input
         * @private
         */
        _onMinMaxPopup: function (event, plugin, $popup, lastValue, $input) {
            var me = this;

            if ($input.get(0).isSameNode(me.$quantitySelect.get(0))) {
                setTimeout(function () {
                    me.$el.removeClass('js--visible');
                }, 100);
            }
        },

        /**
         * Updates the view
         */
        update: function () {
            // update savings
            this.totalSavings = this.opts.initialPrice * this.quantity - this.total;

            if (this.totalSavings > 0) {
                this.$savingsValue.html(
                    this.totalSavings.toLocaleString(this.opts.locale, { minimumFractionDigits: 2 })
                );

                this.$el.addClass('show-savings');
                this.$savings.show();
            } else {
                this.$savings.hide();
                this.$el.removeClass('show-savings');
            }

            // set new sum
            this.$sum.html(
                this.total.toLocaleString(this.opts.locale, { minimumFractionDigits: 2 })
            );

            this.$sum.html(
                this.total.toLocaleString(this.opts.locale, { minimumFractionDigits: 2 })
            );

            if (this.opts.showPopup) {
                this.updatePopup();
            }

            $.publish('plugin/mabpTotalDisplay/onUpdate', [ this, this.total, this.$sum ]);
        },

        /**
         * Hides or shows the min / max purchase info popup.
         */
        updatePopup: function () {
            var me = this;

            if (this.quantity > this.opts.initialQuantity) {
                this.popper.scheduleUpdate();

                if (!this.$el.hasClass('js--visible')) {
                    this.$el.addClass('js--visible');

                    if (me.$quantitySelect.hasClass('mabp-number-input')) {
                        // hide on blur (text field only)
                        this.$quantitySelect.one('blur', function () {
                            me.$el.removeClass('js--visible');
                        });
                    }

                    // hide on body click
                    setTimeout(function () {
                        me._hidePopup = function(e) {
                            me._onBodyClick.call(me, e);
                        };
                        $('body').bind('click', me._hidePopup);
                    }, 150);
                }

            } else {
                this.$el.removeClass('js--visible');

                if (me._hidePopup !== undefined) {
                    $('body').unbind('click', me._hidePopup);
                    me._hidePopup = undefined;
                }
            }
        },

        /**
         * Hides the popup if click target is not the quantity field.
         *
         * @param e
         * @private
         */
        _onBodyClick: function(e) {
            var me = this;

            if ($(e.target).parents('.mabp-number-input, .buybox--quantity, .mabp-block-prices--rows').length) {
                return;
            }

            me.$el.removeClass('js--visible');
        },

        /**
         * Destructor
         */
        destroy: function () {
            var me = this;

            $.unsubscribe(me.getEventName('plugin/mabpBlockPrices/onPriceChange'));
            $.unsubscribe(me.getEventName('plugin/swkweSetBuy/afterUpdatePrice'));
            $.unsubscribe(me.getEventName('plugin/OptionManager/onOverviewPanelSuccess'));
            $.unsubscribe(me.getEventName('plugin/mabpNumberInput/onShowPopup'));

            me._destroy();
        }
    });
})(jQuery);


jQuery(document).ready(function() {
    StateManager.addPlugin('.product--details [data-mabp-total-display="true"]', 'mabpTotalDisplay');
});
