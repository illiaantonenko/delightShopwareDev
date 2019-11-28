//

Ext.define('Shopware.apps.DelightCallback.store.Callback', {
    extend:'Shopware.store.Listing',

    configure: function() {
        return {
            controller: 'DelightCallback'
        };
    },

    model: 'Shopware.apps.DelightCallback.model.Callback'
});