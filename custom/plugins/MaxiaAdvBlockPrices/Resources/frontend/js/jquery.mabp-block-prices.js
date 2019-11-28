;(function($) {
    'use strict';

    /**
     * Check if an element is partially or fully visible in the given container
     * @param container
     * @param element
     * @param partial
     * @returns {boolean|*}
     */
    function checkInView(container, element, partial) {
        var cTop = container.scrollTop;
        var cBottom = cTop + container.clientHeight;

        var eTop = element.offsetTop;
        var eBottom = eTop + element.clientHeight;

        var isTotal = (eTop >= cTop && eBottom <= cBottom);
        var isPartial = partial && (
            (eTop < cTop && eBottom > cTop) ||
            (eBottom > cBottom && eTop < cBottom)
        );

        return (isTotal  || isPartial);
    }

    /**
     * Block prices table plugin
     */
    $.plugin('mabpBlockPrices', {

        defaults: {
            rowsContainerSelector: '.mabp-block-prices--rows',
            rowSelector: '.mabp-block-prices--row',
            referencePriceSelector: '.product--details .mabp-referenceprice',
            quantitySelector: '.product--details #sQuantity',
            swkeProductSetsCheckSelector: '.buybox--article-set',
            activeClass: 'js--selected',
            maxHeight: 0,
            unavailableClass: 'is--unavailable',
            unavailableText: 'Not available',
            maxPurchase: 100,
            minPurchase: 1,
            clickable: true,
            locale: 'de-DE',
            symbol: '€'
        },

        index: null,
        groups: [],
        hasSwkeProductSets: false,

        /**
         * Constructor
         */
        init: function () {
            var me = this;

            this.applyDataAttributes(true, []);

            this.$quantitySelect = $(this.opts.quantitySelector).first();
            this.$rowsContainer = this.$el.find(this.opts.rowsContainerSelector);
            this.$rows = this.$rowsContainer.find(this.opts.rowSelector);
            this.$referencePrice = $(this.opts.referencePriceSelector).first();
            this.hasSwkeProductSets = $(this.opts.swkeProductSetsCheckSelector).length > 0;

            if (!this.opts.minPurchase) {
                this.opts.minPurchase = 1;
            }
            if (!this.opts.maxPurchase) {
                this.opts.maxPurchase = 100;
            }

            this._readGroups();

            var firstAvailableRow = this.$rows.not('.' + me.opts.unavailableClass).first();
            this.select(firstAvailableRow.length ? firstAvailableRow.index() : 0);

            this._initTableHeight();
            this._registerEvents();
        },

        /**
         * Initializes the scrollbar / max height
         *
         * @private
         */
        _initTableHeight: function() {
            var me = this;

            if (this.$el.hasClass('is--scrollable')) {
                this.opts.scrollable = true;
            }

            if (!this.opts.maxHeight) {
                // set fixed height to avoid animation issues when animating active rows
                setTimeout(function () {
                    me.$el.css('min-height', me.$el.height() + 2);
                }, 100);

            } else {

                // init custom scrollbar
                setTimeout(function() {

                    if (me.$rowsContainer.get(0).scrollHeight > me.opts.maxHeight) {

                        me.$rowsContainer.css('overflow-y', 'auto');

                        me.simpleBar = new SimpleBar(me.$rowsContainer.get(0), {
                            autoHide: false
                        });

                        me._on(me.simpleBar.getScrollElement(), 'scroll', function (event) {
                            me._updateScrollFade.call(me, event.target);
                        });

                        me._updateScrollFade.call(me);
                    }
                }, 220);
            }
        },

        /**
         * Binds required event listeners
         *
         * @private
         */
        _registerEvents: function () {
            var me = this;

            me._on(this.$quantitySelect, 'change', function () {
                me._onQuantityChange.call(me, $(this).val());
            });

            if (this.opts.clickable) {
                this.$el.addClass('is--clickable');

                this._on(this.$rows, 'click', function(event) {
                    return me._onRowClick.call(me, event, $(this));
                });
            }

            if (this.hasSwkeProductSets) {
                $.subscribe(me.getEventName('plugin/swkweSetBuy/afterUpdatePrice'), $.proxy(me._onSwkeProductSetsChange, me));
            }
        },

        /**
         * Selects the price group at index and updates the reference price.
         *
         * @param index
         */
        select: function (index) {
            var me = this,
                row = this.$rows.get(index),
                group = this.groups[index];

            this.index = index;

            this.$rows.removeClass(this.opts.activeClass);
            row.classList.add(this.opts.activeClass);

            this._updateReferencePrice(group.referenceprice);

            if (me.simpleBar !== undefined) {
                setTimeout(function () {
                    me.scrollToRow.call(me, row);
                }, 220);
            }

            $.publish('plugin/mabpBlockPrices/onPriceChange', [
                me,
                me.groups[index],
                me.$quantitySelect.val(),
                $(row)
            ]);
        },

        /**
         * Scrolls animated to the given row.
         *
         * @param row
         */
        scrollToRow: function (row) {
            var me = this,
                $container = $(me.simpleBar.getScrollElement());

            if (!checkInView(me.simpleBar.getScrollElement(), row)) {
                var top = $(row).position().top;
                var max = $container.clientHeight - me.$rowsContainer.height();
                top = top > max ? max : top;

                $container.stop().animate({ scrollTop: top}, 350);
            }
        },

        /**
         * Adds fade-bottom / fade-top classes based depending on current scroll position.
         *
         * @private
         */
        _updateScrollFade: function () {
            var me = this,
                $container = $(me.simpleBar.getScrollElement()),
                scrollTop = $container.get(0).scrollTop,
                scrollBottom = scrollTop + $container.height(),
                contentHeight = $container.find('.simplebar-content').get(0).scrollHeight,
                offset = 10;

            if (scrollTop > offset) {
                me.$el.addClass('scroller-fade-top');
            } else {
                me.$el.removeClass('scroller-fade-top');
            }

            if (scrollBottom <= (contentHeight - offset)) {
                me.$el.addClass('scroller-fade-bottom');
            } else {
                me.$el.removeClass('scroller-fade-bottom');
            }
        },

        /**
         * Selects the matching price group based on new quantity
         *
         * @param quantity
         * @private
         */
        _onQuantityChange: function(quantity) {
            var me = this;

            this.groups.forEach(function (group) {
                if (quantity >= group.from && (quantity <= group.to || !group.to)) {

                    var $row = $( me.$rows.get(group.index) );

                    if ($row.hasClass(me.opts.unavailableClass)) {
                        return;
                    }

                    me.select(group.index);
                }
            });
        },

        /**
         * Updates quantity select on row click.
         *
         * @param event
         * @param $row
         * @private
         */
        _onRowClick: function(event, $row) {
            var index = $row.index();

            if ($row.hasClass(this.opts.unavailableClass)) {
                return;
            }

            var quantity = JSON.parse(JSON.stringify( this.groups[index].from ));

            if (quantity > this.opts.maxPurchase) {
                quantity = this.opts.maxPurchase;

            } else if (quantity < this.opts.minPurchase) {
                quantity = this.opts.minPurchase;
            }

            this.$quantitySelect.val(quantity);
            this.$quantitySelect.trigger('change').trigger('update');
        },

        /**
         * Updates the reference price that is displayed below the table.
         *
         * @param price
         * @private
         */
        _updateReferencePrice: function (price) {
            if ( ! this.$referencePrice.length) {
                return;
            }

            // update reference price
            this.$referencePrice.html(
                price.toLocaleString(this.opts.locale, { minimumFractionDigits: 2 })
                + ' ' + this.opts.symbol
            );
        },

        /**
         * Reads price group data from DOM
         *
         * @private
         */
        _readGroups: function () {
            var me = this,
                i = 0,
                group;

            me.groups = [];

            this.$rows.each(function () {
                group = {
                    index: i,
                    from: parseInt( $(this).attr('data-from') ),
                    to: parseInt( $(this).attr('data-to') ),
                    price: parseFloat( $(this).attr('data-price') ),
                    referenceprice: parseFloat( $(this).attr('data-referenceprice') )
                };

                if ($(this).attr('data-pseudoprice')) {
                    group.pseudoprice = parseFloat( $(this).attr('data-pseudoprice') );
                }

                me.groups.push(group);
                i++;
            });
        },

        /**
         * Take max purchase into account on _onSwkeProductSetsChange
         *
         * @param event
         * @param plugin
         * @param price
         */
        _onSwkeProductSetsChange: function (event, plugin, price) {
            var me = this,
                maxPurchase = plugin._maxPurchase;

            this.opts.maxPurchase = maxPurchase;

            this.groups.forEach(function (group) {

                var $row = $( me.$rows.get(group.index) );

                if (group.from > maxPurchase || group.from < me.opts.minPurchase) {

                    if (group.index === me.index && group.index > 0) {
                        me.select(group.index - 1);
                    }

                    me._setRowAvailable($row, false);
                } else {
                    me._setRowAvailable($row, true);
                }
            });
        },

        /**
         * Changes the availability state of a row
         *
         * @param $row
         * @param enabled
         * @private
         */
        _setRowAvailable: function ($row, enabled) {
            if (!enabled) {
                $row.addClass(this.opts.unavailableClass);
                $row.attr('title', this.opts.unavailableText);
            } else {
                $row.removeClass(this.opts.unavailableClass);
                $row.removeAttr('title');
            }
        },

        /**
         * Destroy the plugin instance
         */
        destroy: function () {
            var me = this;
            me._destroy();
        }
    });
})(jQuery);

jQuery(document).ready(function() {
    StateManager.addPlugin('.product--details [data-mabp-block-prices="true"]', 'mabpBlockPrices');
});
