<?php

    namespace Talexan\Credit\Model\ResourceModel;


class Coin extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}
	
	protected function _construct()
	{
		$this->_init('customer_credit_talexan', 'entity_id');
	}
	
}