<?php

namespace Jahvi\NewOrderNotification\Observer;

use Magento\Catalog\Helper\Image;
use Magento\Sales\Model\OrderFactory;
use Magento\Framework\Event\Observer;
use Magento\Directory\Model\CountryFactory;
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

    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    protected $countryFactory;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    public function __construct(
        ScopeConfigInterface $globalConfig,
        OrderFactory $orderFactory,
        CountryFactory $countryFactory,
        Image $imageHelper
    ) {
        $this->globalConfig   = $globalConfig;
        $this->orderFactory   = $orderFactory;
        $this->countryFactory = $countryFactory;
        $this->imageHelper    = $imageHelper;
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
            $productImage = $this->imageHelper->init($product, 'product_thumbnail_image');

            $shippingCountryCode = $order->getShippingAddress()->getCountryId();
            $shippingCountry     = $this->countryFactory->create()->loadByCode($shippingCountryCode);

            $pusher->trigger(
                'non_channel',
                'new_order',
                [
                    'product_name'     => $product->getName(),
                    'product_image'    => $productImage->getUrl(),
                    'product_url'      => $product->getProductUrl(),
                    'shipping_city'    => $shippingCity,
                    'shipping_country' => $shippingCountry->getName(),
                ]
            );
        }

        return $this;
    }
}
