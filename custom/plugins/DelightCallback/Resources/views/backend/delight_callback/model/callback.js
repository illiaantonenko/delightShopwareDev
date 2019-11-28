Ext.define('Shopware.apps.DelightCallback.model.Callback', {
    extend: 'Shopware.data.Model',

    configure: function () {
        return {
            controller: 'DelightCallback',
            detail: 'Shopware.apps.DelightCallback.view.detail.Callback'
        };
    },


    fields: [
        { name: 'id', type: 'int', useNull: true },
        { name: 'name', type: 'string' },
        { name: 'phone', type: 'string' },
        { name: 'active', type: 'boolean' },
        { name: 'createDate', type: 'date' },
    ]
});

