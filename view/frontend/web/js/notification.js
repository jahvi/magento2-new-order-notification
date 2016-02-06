define([
    'jquery',
    'pusherjs',
    'notificationFx',
    'jquery/ui'
], function ($, Pusher) {
    'use strict';

    $.widget('jahvi.newOrderNotification', {
        _create: function () {
            var pusher = new Pusher(this.options.appKey, {
                encrypted: true
            });

            var channel = pusher.subscribe('non_channel');

            channel.bind('new_order', function (data) {
                var notification = new NotificationFx({
                    message: '<div class="ns-thumb"><img src="' + data.product_image + '"/></div><div class="ns-content"><p>Someone in <strong>' + data.shipping_city + ', ' + data.shipping_country + '</strong> just purchased <a href="' + data.product_url + '">' + data.product_name + '</a>.</p></div>',
                    layout: 'other',
                    ttl: 6000,
                    effect: 'thumbslider',
                    type: 'notice'
                });

                notification.show();
            });
        }
    });

    return $.jahvi.newOrderNotification;
});
