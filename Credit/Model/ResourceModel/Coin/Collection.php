<?php

namespace Talexan\Credit\Model\ResourceModel\Coin;

    class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
    {
        protected $_idFieldName = 'entity_id';
 //       protected $_eventPrefix = 'talexan_customer_coin_collection';
 //       protected $_eventObject = 'coin_collection';

        /**
         * Define resource model
         *  @return void
         */
        protected function _construct()
        {
            $this->_init('Talexan\Credit\Model\Coin', 'Talexan\Credit\Model\ResourceModel\Coin');
        }
    }
