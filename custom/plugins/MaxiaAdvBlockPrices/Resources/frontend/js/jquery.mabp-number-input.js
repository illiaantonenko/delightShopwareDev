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
     * Quantity text field with plus / minus buttons.
     */
    $.plugin('mabpNumberInput', {

        defaults: {
            inputSelector: '.mabp-number-input--field',
            plusButtonSelector: '.plus-button',
            minusButtonSelector: '.minus-button',
            buttonDisabledClass: 'js--disabled',
            min: 1,
            max: null,
            step: 1,
            debounce: 300,
            showPopup: false,
            popupTemplate: '<div class="mabp--popup" role="tooltip"></div>',
            popupArrowTemplate: '<span class="mabp--popup-arrow" x-arrow></span>',
            popupCloseBtnTemplate: '<div class="mabp--popup-close"><i class="icon--cross2"></i></div>',
            popupPlacement: 'top',
            msgMaxPurchase: '<i class="icon--info2"></i> Maximalabnahmemenge wurde erreicht',
            msgMinPurchase: '<i class="icon--info2"></i> Mindestabnahmemenge wurde erreicht'
        },

        popperConfig: {
            placement: 'top',
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

            // create template
            this.$plusButton = this.$el.find(this.opts.plusButtonSelector);
            this.$minusButton = this.$el.find(this.opts.minusButtonSelector);
            this.$input = this.$el.find(this.opts.inputSelector);

            this.popperConfig.placement = this.opts.popupPlacement;

            me.lastValue = parseInt(this.$input.val());
            this.setValue(this.$input.val());
            this.$minusButton.removeClass(this.opts.buttonDisabledClass);

            if (me.opts.showPopup) {
                this.initPopups();
            }

            this.registerEvents();
        },

        /**
         * Initializes the min / max purchase warning popups.
         */
        initPopups: function () {
            var me = this;

            me.$popupMax = $(this.opts.popupTemplate)
                .addClass('popup-max')
                .html(me.opts.msgMaxPurchase)
                .append(me.opts.popupCloseBtnTemplate)
                .append(me.opts.popupArrowTemplate);

            me.$popupMin = $(this.opts.popupTemplate)
                .addClass('popup-min')
                .html(me.opts.msgMinPurchase)
                .append(me.opts.popupCloseBtnTemplate)
                .append(me.opts.popupArrowTemplate);

            me.$el.append(me.$popupMax);
            me.$el.append(me.$popupMin);

            this.popperMax = new Popper(this.$el, this.$popupMax.get(0), this.popperConfig);
            this.popperMin = new Popper(this.$el, this.$popupMin.get(0), this.popperConfig);
        },

        /**
         * Registers required events
         */
        registerEvents: function () {
            var me = this;

            /*
             * Prevent non-numeric inputs
             */
            this._on(this.$input, 'keydown', function(event) {
                if ( (event.keyCode >= 48 && event.keyCode <= 57)                                   // numbers 0-9
                    || (event.keyCode >= 96 && event.keyCode <= 105)                                // numbers 0-9 numpad
                    || event.keyCode === 8 || event.keyCode === 46 || event.keyCode === 13          // backspace, delete, enter
                    || (event.keyCode === 65 && (event.ctrlKey === true || event.metaKey === true)) // ctrl/cmd + a
                    || (event.keyCode === 67 && (event.ctrlKey === true || event.metaKey === true)) // ctrl/cmd + c
                    || (event.keyCode === 88 && (event.ctrlKey === true || event.metaKey === true)) // ctrl/cmd + x
                    || (event.keyCode === 37 || event.keyCode === 39)                               // arrow left / right
                ) {
                    return true;
                }
                else if (event.keyCode === 38) {   // up arrow
                    me.increase();
                }
                else if (event.keyCode === 40) {   // down arrow
                    me.decrease();
                }

                event.stopPropagation();
                event.preventDefault();
                return false;
            });

            /*
             * Apply value when typing
             */
            this._on(this.$input, 'focus', function() {

                me.$input.on(me.getEventName('keyup'), debounce(function () {

                    if (me.$input.val().length > 0) {
                        me.setValue(me.$input.val());
                    }
                }, me.opts.debounce, false));
            });

            /*
             * Apply value on blur
             */
            this._on(this.$input, 'blur', function(e) {

                me.$input.off(me.getEventName('keyup'));

                if (me.$input.val().length === 0) {
                    me.setValue(me.opts.min);
                } else {
                    me.setValue(me.$input.val());
                }
            });

            this._on(this.$plusButton, 'click', $.proxy(this.increase, me));
            this._on(this.$minusButton, 'click', $.proxy(this.decrease, me));

            /*
             * Use when value has been change programmatically
             */
            this._on(this.$input, 'update', function(e) {
                me.setValue(me.$input.val());
            });

            /*
             * Popup event listeners
             */
            if (me.opts.showPopup) {

                me._on(this.$input, 'blur', function () {
                    me.$popupMax.removeClass('js--visible');
                    me.$popupMin.removeClass('js--visible');
                });

                me._on(this.$input, 'blur', function () {
                    me.$popupMax.removeClass('js--visible');
                    me.$popupMin.removeClass('js--visible');
                });

                me._on(this.$popupMax, 'click', function (event) {
                    me.$popupMax.removeClass('js--visible');
                });

                me._on(this.$popupMin, 'click', function (event) {
                    me.$popupMin.removeClass('js--visible');
                });

                me._on(this.$input, 'numberchange', function (event) {
                    me.updatePopups.call(me, event);
                });
            }
        },

        /**
         * Decreases the value by step
         */
        decrease: function() {
            if ( ! this.$minusButton.hasClass(this.opts.buttonDisabledClass)) {
                this.setValue(this.getValue() - this.opts.step);
            }
        },

        /**
         * Increases the value by step
         */
        increase: function() {
            if ( ! this.$plusButton.hasClass(this.opts.buttonDisabledClass)) {
                this.setValue(this.getValue() + this.opts.step);
            }
        },

        /**
         * Sets the input value respecting the min/max values and shows or hides the plus/minus buttons accordingly.
         *
         * @param value
         */
        setValue: function(value) {
            var me = this;

            value = parseInt(value);

            if ( ! value) {
                value = this.opts.min;
            }

            var inputValue = value;

            // check if the value needs rounding
            if (this.opts.step > 1) {

                if (value % this.opts.step > 0) {
                    value = this._roundValue(value);
                }
            }

            if (value <= this.opts.min) {
                this.$plusButton.removeClass(this.opts.buttonDisabledClass);
                this.$minusButton.addClass(this.opts.buttonDisabledClass);
                value = this.opts.min;
                $.publish('plugin/mabpNumberInput/minPurchaseReached', [ me, value, inputValue, me.$input ]);
            } else {
                this.$minusButton.removeClass(this.opts.buttonDisabledClass);
            }

            if (this.opts.max !== null && value >= this.opts.max) {
                this.$minusButton.removeClass(this.opts.buttonDisabledClass);
                this.$plusButton.addClass(this.opts.buttonDisabledClass);
                value = this.opts.max;
                $.publish('plugin/mabpNumberInput/maxPurchaseReached', [ me, value, inputValue, me.$input ]);
            } else {
                this.$plusButton.removeClass(this.opts.buttonDisabledClass);
            }

            if (parseInt(this.$input.val()) !== value) {
                this.$input.val(value);
            }

            if (me.lastValue !== value) {
                me.lastValue = value;
                me.$input.trigger('numberchange').trigger('change');
                $.publish('plugin/mabpNumberInput/onChange', [ me, value, inputValue, me.$input ]);
            }
        },

        /**
         * Shows or hides the min/max purchase popup
         */
        updatePopups: function () {
            var me = this;

            // check if max purchase exceeded, hide or show popup accordingly
            if (me.opts.max !== null && me.lastValue >= me.opts.max) {

                if (!me.$popupMax.hasClass('js--visible')) {
                    me.popperMax.scheduleUpdate();
                    me.$popupMax.addClass('js--visible');
                    $.publish('plugin/mabpNumberInput/onShowPopup', [ me, me.$popupMax, me.lastValue, me.$input ]);
                }

            } else {
                me.$popupMax.removeClass('js--visible');
            }

            // check if min purchase exceeded, hide or show popup accordingly
            if (me.lastValue <= me.opts.min && me.opts.min > 1) {

                if (!me.$popupMin.hasClass('js--visible')) {
                    me.popperMin.scheduleUpdate();
                    me.$popupMin.addClass('js--visible');
                    $.publish('plugin/mabpNumberInput/onShowPopup', [ me, me.$popupMin, me.lastValue, me.$input ]);
                }

            } else {
                me.$popupMin.removeClass('js--visible');
            }
        },

        /**
         * Returns the current value
         */
        getValue: function() {
            var value = this.$input.val();

            return value
                ? parseInt(value)
                : this.opts.min;
        },

        /**
         * Rounds the value up or down to the next step
         *
         * @param value
         * @returns {*}
         */
        _roundValue: function(value) {
            var rest = value % this.opts.step;

            if (rest >= (this.opts.step / 2)) {
                return value + (this.opts.step - rest);
            } else {
                return value - rest;
            }
        },

        /**
         * Destructor
         */
        destroy: function () {
            var me = this;
            me._destroy();
        }
    });

    $(document).ready(function () {
        StateManager.addPlugin('[data-mabp-number-input="true"]', 'mabpNumberInput');
    });
})(jQuery);
