## New Order Notification

Display a real time notification on the frontend of your Magento 2 store when someone places an order. The notification contains the
latest product in the order and the location that the items were shipped to.

![Sample Notification](http://i.imgur.com/DNvtVuP.gif)

You will need to create a [FREE Pusher account](https://dashboard.pusher.com/accounts/sign_up) to be able to use this extension.

### Installation instructions

1. Require latest stable version `composer require jahvi/new-order-notification`
2. Enable extension `php bin/magento module:enable Jahvi_NewOrderNotification`
3. Deploy static content `php bin/magento setup:static-content:deploy`
4. Clear cache `php bin/magento cache:clean`

### How to use

1. Go to Stores > Configuration > Sales > Checkout
2. Enter your app credentials from the Pusher website
