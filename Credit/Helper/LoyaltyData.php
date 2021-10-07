<?php

namespace Talexant\Credit\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class LoyaltyData extends AbstractHelper
{
    /**
     * $this->helperData->getGeneralConfig('enable');
     */ 

	const XML_PATH_LOAYLTY_PROGRAM = 'loyalty_program/';

	public function getConfigValue($field, $storeId = null)
	{
		return $this->scopeConfig->getValue(
			$field, ScopeInterface::SCOPE_STORE, $storeId
		);
	}

	public function getGeneralConfig($code, $storeId = null)
	{

		return $this->getConfigValue(self::XML_PATH_LOAYLTY_PROGRAM .'general/'. $code, $storeId);
	}

}