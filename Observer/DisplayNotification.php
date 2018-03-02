<?php
/**
 * Display notification event listener
 *
 * @author Javier Villanueva <javiervd@gmail.com>
 */
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
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $globalConfig;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    private $orderFactory;

    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    private $countryFactory;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    private $imageHelper;

    /**
     * Setup initial dependencies
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $globalConfig
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Directory\Model\CountryFactory $countryFactory
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Pusher\PusherFactory $pusherFactory
     */
    public function __construct(
        ScopeConfigInterface $globalConfig,
        OrderFactory $orderFactory,
        CountryFactory $countryFactory,
        Image $imageHelper
    ) {
        $this->globalConfig = $globalConfig;
        $this->orderFactory = $orderFactory;
        $this->countryFactory = $countryFactory;
        $this->imageHelper = $imageHelper;
    }

    /**
     * Trigger pusher event with latest order information
     *
     * @param  \Magento\Framework\Event\Observer $observer
     * @return \Jahvi\NewOrderNotification\Observer\DisplayNotification
     */
    public function execute(Observer $observer)
    {
        if (!$this->globalConfig->getValue('checkout/newordernotification/enabled')) {
            return $this;
        }

        $appId = $this->globalConfig->getValue('checkout/newordernotification/app_id');
        $appKey = $this->globalConfig->getValue('checkout/newordernotification/app_key');
        $appSecret = $this->globalConfig->getValue('checkout/newordernotification/app_secret');
        $cluster = $this->globalConfig->getValue('checkout/newordernotification/cluster');

        $pusher = new \Pusher\Pusher(
            $appKey,
            $appSecret,
            $appId,
            ['encrypted' => true, 'cluster' => $cluster]
        );

        // Get latest order
        $orderId = $observer->getEvent()->getOrderIds()[0];

        $order = $this->orderFactory->create()->load($orderId);

        // Get last product in order data
        $product = $order->getAllVisibleItems()[0]->getProduct();
        $shippingCity = $order->getShippingAddress()->getCity();
        $productImage = $this->imageHelper->init($product, 'product_thumbnail_image');

        // Get shipping city and country
        $shippingCountryCode = $order->getShippingAddress()->getCountryId();
        $shippingCountry = $this->countryFactory->create()->loadByCode($shippingCountryCode);

        // Trigger pusher event with collected data
        $resp = $pusher->trigger(
            'non_channel',
            'new_order',
            [
                'product_name' => $product->getName(),
                'product_image' => $productImage->getUrl(),
                'product_url' => $product->getProductUrl(),
                'shipping_city' => $shippingCity,
                'shipping_country' => $shippingCountry->getName(),
            ]
        );

        return $this;
    }
}
