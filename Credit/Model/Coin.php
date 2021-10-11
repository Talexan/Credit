<?php

    namespace Talexan\Credit\Model;

    class Coin extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
    {
	    const CACHE_TAG = 'customer_credit_talexan';

	    protected $_cacheTag = 'customer_credit_talexan';

	    protected $_eventPrefix = 'customer_credit_talexan';

	    protected function _construct()
	    {
		    $this->_init('Talexan\Credit\Model\ResourceModel\Coin');
	    }

	    public function getIdentities()
	    {
	    	return [self::CACHE_TAG . '_' . $this->getId()];
	    }

	    public function getDefaultValues()
	    {
	    	$values = [];

	    	return $values;
	    }
    }