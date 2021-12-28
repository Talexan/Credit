<?php

namespace Talexan\Credit\Model;

    class Coin extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
    {
        const TYPE_PURCHASE_PRODUCT = 1;
        const TYPE_SET_ADMIN = 2;
        const TYPE_GLOBAL_PROMOTIONAL = 3;

        const CACHE_TAG = 'customer_credit_history';

        protected $_cacheTag = 'customer_credit_history';

        protected $_eventPrefix = 'customer_credit_history';

        protected function _construct()
        {
            $this->_init('Talexan\Credit\Model\ResourceModel\Coin');
        }

        public function getIdentities()
        {
            return [self::CACHE_TAG . '_' . $this->getId()];
        }

        public function getTypes($type = null)
        {
            $types = self::getTypesAsArray();
            if ($type) {
                return isset($types[$type]) ? $types[$type] : null;
            }
            return $types;
        }

        protected function getTypesAsArray()
        {
            return [
                static::TYPE_PURCHASE_PRODUCT => __('Purchasing products'),
                static::TYPE_SET_ADMIN => __('Credit set admin'),
                static::TYPE_GLOBAL_PROMOTIONAL => __('Global promotional gift')
            ];
        }
    }
