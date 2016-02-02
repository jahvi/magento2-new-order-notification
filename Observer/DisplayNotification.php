<?php
namespace Jahvi\NewOrderNotification\Observer;

use Magento\Framework\Event\ObserverInterface;

class DisplayNotification implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        var_dump($observer->getEvent());die;

        return $this;
    }
}
