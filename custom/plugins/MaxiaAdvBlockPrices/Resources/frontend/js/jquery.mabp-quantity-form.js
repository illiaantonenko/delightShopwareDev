;(function($) {
    'use strict';

    var debounce = function(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };

    /**
     * Handles the quantity input form on the cart page.
     */
    $.plugin('mabpQuantityForm', {

        defaults: {
            formSelector: '.mabp-quantity-form',
            inputSelector: '.mabp-quantity-form .mabp-number-input--field',
            submitBtnSelector: '.mabp-quantity-form .btn--change-quantity',
            quantityContainerSelector: '.mabp-quantity-form--quantity',
            activeClass: 'is--active',
            submitMethod: 'timeout',
            timeout: 2000
        },

        /**
         * Constructor
         */
        init: function () {
            var me = this;

            this.applyDataAttributes(true, []);

            this.$form = this.$el.find(this.opts.formSelector).first();
            this.$input = this.$el.find(this.opts.inputSelector).first();
            this.$submitBtn = this.$el.find(this.opts.submitBtnSelector).first();

            this.bindEvents();
        },

        /**
         * Binds listeners
         */
        bindEvents: function () {
            var me = this;

            if (me.opts.submitMethod === 'button') {

                // show / hide submit buttons on quantity change
                this._on(this.$input, 'change', function () {

                    if (!me.$form.hasClass(me.opts.activeClass))
                        me.$form.addClass(me.opts.activeClass);
                });

            } else {

                // auto submit after timeout
                this._on(this.$input, 'change', debounce(function () {

                    if (me.isSubmitted !== true) {
                        me.$form.submit();
                        me.isSubmitted = true;
                    }

                }, me.opts.timeout));

                this._on(this.$input, 'blur', debounce(function () {

                    if (me.isSubmitted !== true) {
                        me.$form.submit();
                        me.isSubmitted = true;
                    }

                }, 1000));
            }

            // loading indicator on submit
            this._on(this.$form, 'submit', function () {
                $.loadingIndicator.open();
            });
        },

        /**
         * Destroy the plugin instance
         */
        destroy: function () {
            var me = this;
            me._destroy();
        }
    });

    StateManager.addPlugin('[data-mabp-quantity-form="true"]', 'mabpQuantityForm');

})(jQuery);
