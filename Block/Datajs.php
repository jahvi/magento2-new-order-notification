<?php
namespace Jahvi\NewOrderNotification\Block;

class Datajs extends \Magento\Framework\View\Element\Template
{
    const PUSHER_APP_KEY = 'checkout/newordernotification/app_key';

    public function getAppKey()
    {
        return $this->_scopeConfig->getValue(
            self::PUSHER_APP_KEY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            null
        );
    }
}