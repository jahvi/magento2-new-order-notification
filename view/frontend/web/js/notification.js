/* global define, NotificationFx */
define([
    'jquery',
    'pusherjs',
    'notificationFx',
    'jquery/ui',
    'mage/translate'
], function ($, Pusher) {
    'use strict';

    $.widget('jahvi.newOrderNotification', {
        _create: function () {
            var pusher = new Pusher(this.options.appKey, {
                encrypted: true
            });

            var channel = pusher.subscribe('non_channel');

            channel.bind('new_order', function (data) {
                var message = $.mage.__('Someone in %1 just purchased %2')
                .replace('%1', '<strong>' + data.shipping_city + ', ' + data.shipping_country + '</strong>')
                .replace('%2', '<a href="' + data.product_url + '">' + data.product_name + '</a>');

                var notification = new NotificationFx({
                    message: '<div class="ns-thumb"><img src="' + data.product_image + '"/></div><div class="ns-content"><p>' + message + '</p></div>',
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
