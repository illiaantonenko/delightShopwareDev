Ext.define('Shopware.apps.DelightCallback', {
    extend: 'Enlight.app.SubApplication',

    name:'Shopware.apps.DelightCallback',

    loadPath: '{url action=load}',
    bulkLoad: true,

    controllers: [ 'Main' ],

    views: [
        'list.Window',
        'list.Callback',

        'detail.Callback',
        'detail.Window'
    ],

    models: [ 'Callback' ],
    stores: [ 'Callback' ],

    launch: function() {
        return this.getController('Main').mainWindow;
    }
});