;(function($) {
    'use strict';

    /**
     * Variant change listener
     *
     * Initializes plugins on ajax variant change.
     */
    $.plugin('mabpVariantChangeListener', {

        init: function () {
            $.subscribe(this.getEventName('plugin/swAjaxVariant/onRequestData'), function(event, plugin) {

                StateManager.addPlugin('.product--details [data-mabp-current-price="true"]', 'mabpCurrentPrice');
                StateManager.addPlugin('.product--details [data-mabp-block-prices="true"]', 'mabpBlockPrices');
                StateManager.addPlugin('.product--details [data-mabp-total-display="true"]', 'mabpTotalDisplay');
                StateManager.addPlugin('.product--details [data-mabp-number-input="true"]', 'mabpNumberInput');
            });
        }
    });
})(jQuery);

jQuery(document).ready(function() {
    StateManager.addPlugin('body.is--ctl-detail', 'mabpVariantChangeListener');
});
