;(function($) {
    'use strict';

    /**
     * Compatibility with Module Factory Productoptions Plugin
     *
     * Bind #sQuantity 'numberchange' event instead of 'change' to avoid duplicate events which
     * will trigger the ajax loading multiple times.
     */
    var $body = $('body');

    $.overridePlugin('swpProductOptions', {

        registerEventListeners: function() {
            var me = this,
                $input = $('#sQuantity');

            me.superclass.registerEventListeners.apply(me, arguments);

            if ($input.hasClass('mabp-number-input--field')) {

                // rebind change event
                $body.off(me.getEventName('change'), '#sQuantity');

                $body.on(me.getEventName('numberchange'), '#sQuantity', function (event) {
                    me.onChange.call(me, event);
                });
            }
        }
    });
})(jQuery);