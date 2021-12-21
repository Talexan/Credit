<?php

namespace Talexan\Credit\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * $this->helperData->getGeneralConfig('enable');
     */

    const XML_PATH_LOAYLTY_PROGRAM = 'loyalty_programm/';

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_LOAYLTY_PROGRAM . 'general/' . $code, $storeId);
    }

    /**
     * Calculate received coins
     * @param float $price
     * @return float|int
     */
    public function calculateReceivedCoins($price)
    {
        return $this->getGeneralConfig('percent_purchase') * $price /100;
    }
}
