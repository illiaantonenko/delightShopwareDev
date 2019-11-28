//

Ext.define('Shopware.apps.DelightCallback.view.list.Callback', {
    extend: 'Shopware.grid.Panel',
    alias:  'widget.callback-listing-grid',
    region: 'center',

    configure: function() {
        return {
            detailWindow: 'Shopware.apps.DelightCallback.view.detail.Window'
        };
    }
});
