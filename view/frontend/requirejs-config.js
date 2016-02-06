var config = {
    paths: {
        notificationFx: 'Jahvi_NewOrderNotification/js/notificationFx',
        modernizr: 'Jahvi_NewOrderNotification/js/modernizr.custom'
    },
    map: {
        '*': {
            pusherjs: 'https://js.pusher.com/3.0/pusher.min.js',
            newOrderNotification: 'Jahvi_NewOrderNotification/js/notification'
        }
    },
    shim: {
        notificationFx: {
            deps: ['modernizr', 'jquery'],
            exports: 'NotificationFx'
        },
        modernizr: {
            exports: 'Modernizr'
        }
    }
};