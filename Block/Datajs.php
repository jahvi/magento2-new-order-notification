<?php
/**
 * Data JS block
 *
 * @author Javier Villanueva <javiervd@gmail.com>
 */
namespace Jahvi\NewOrderNotification\Block;

class Datajs extends \Magento\Framework\View\Element\Template
{
    const PUSHER_APP_KEY = 'checkout/newordernotification/app_key';

    /**
     * Get pusher app key from config settings
     *
     * @return string
     */
    public function getAppKey()
    {
        return $this->_scopeConfig->getValue(
            self::PUSHER_APP_KEY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            null
        );
    }
}