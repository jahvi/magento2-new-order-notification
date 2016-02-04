<?php
namespace Jahvi\NewOrderNotification\Block;

class Datajs extends \Magento\Framework\View\Element\Template
{
    const PUSHER_APP_ID     = 'checkout/newordernotification/app_id';
    const PUSHER_APP_KEY    = 'checkout/newordernotification/app_key';
    const PUSHER_APP_SECRET = 'checkout/newordernotification/app_secret';

    public function getAppId()
    {
        return $this->_scopeConfig->getValue(
            self::PUSHER_APP_ID,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            null
        );
    }

    public function getAppKey()
    {
        return $this->_scopeConfig->getValue(
            self::PUSHER_APP_KEY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            null
        );
    }

    public function getAppSecret()
    {
        return $this->_scopeConfig->getValue(
            self::PUSHER_APP_SECRET,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            null
        );
    }
}