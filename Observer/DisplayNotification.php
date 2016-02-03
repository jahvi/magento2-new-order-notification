<?php
namespace Jahvi\NewOrderNotification\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class DisplayNotification implements ObserverInterface
{
    /**
     * Global configuration storage.
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $globalConfig;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    public function __construct(ScopeConfigInterface $globalConfig, \Magento\Sales\Model\OrderFactory $orderFactory) {
        $this->globalConfig = $globalConfig;
        $this->orderFactory = $orderFactory;
    }

    public function execute(Observer $observer)
    {
        if ($this->globalConfig->getValue('checkout/newordernotification/enabled')) {

            $appId     = $this->globalConfig->getValue('checkout/newordernotification/app_id');
            $appKey    = $this->globalConfig->getValue('checkout/newordernotification/app_key');
            $appSecret = $this->globalConfig->getValue('checkout/newordernotification/app_secret');

            $pusher = new \Pusher($appKey, $appSecret, $appId, ['encrypted' => true, 'debug' => true]);

            $orderId = $observer->getEvent()->getOrderIds()[0];

            $order = $this->orderFactory->create()->load($orderId);

            $product      = $order->getAllVisibleItems()[0]->getProduct();
            $shippingCity = $order->getShippingAddress()->getCity();

            $pusher->trigger(
                'non_channel',
                'new_order',
                [
                    'product_name'  => $product->getName(),
                    'product_url'   => $product->getProductUrl(),
                    'shipping_city' => $shippingCity,
                ]
            );

        }

        return $this;
    }
}
