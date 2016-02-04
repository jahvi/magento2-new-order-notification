define([
    'jquery',
    'pusherjs',
    'jquery/ui',
], function ($, Pusher) {
    'use strict';

    $.widget('jahvi.newOrderNotification', {
        _create: function () {
            var pusher = new Pusher(this.options.appKey, {
                encrypted: true
            });

            var channel = pusher.subscribe('non_channel');

            channel.bind('new_order', function (data) {
                console.log(data);
            });
        }
    });

    return $.jahvi.newOrderNotification;
});