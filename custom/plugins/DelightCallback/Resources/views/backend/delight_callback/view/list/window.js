//

Ext.define('Shopware.apps.DelightCallback.view.list.Window', {
    extend: 'Shopware.window.Listing',
    alias: 'widget.callback-list-window',
    height: 450,
    title : '{s name=window_title}Callback listing{/s}',

    configure: function() {
        return {
            listingGrid: 'Shopware.apps.DelightCallback.view.list.Callback',
            listingStore: 'Shopware.apps.DelightCallback.store.Callback'
        };
    }
});