<?php

namespace Talexan\Credit\Model\Payment\Method;

use Magento\Checkout\Model\Cart;
use Talexan\Credit\Model\ResourceModel\Coin\CollectionFactory;
use Magento\Customer\Model\Session;

class ConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    /**
     * @var \Magento\Checkout\Model\Cart $cart
     */
    private $_cart;

    /**
     * @var \Talexan\Credit\Model\ResourceModel\Coin\CollectionFactory $coinCollectionFactory
     */
    private $_coinCollectionFactory;

    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    private $_customerSession;
    
    public function __construct(
        \Magento\Checkout\Model\Cart $cart,
        \Talexan\Credit\Model\ResourceModel\Coin\CollectionFactory $coinCollectionFactory,
        \Magento\Customer\Model\Session $customerSession
    ){
        $this->_cart = $cart;
        $this->_coinCollectionFactory = $coinCollectionFactory;
        $this->_customerSession = $customerSession;
    }

    public function getConfig()
    {   // window.checkout.payment.loyaltycoin.(customerCoinsAmount|canActive)
        return [
            // 'key' => 'value' pairs of configuration
            'payment' => [
               \Talexan\Credit\Model\Payment\Method\LoyaltyCoin::CODE => [
                   'customerCoinsAmount' => $this->_getCustomerCoinsAmount(), // Magento_Checkout/js/model/quote::totals
                   'canActive' => $this->_getCanActiveValidator()
               ]
            ]
        ];
    }

    /**
     * @return int|null
     */
    private function _getCustomerCoinsAmount(){
        $customer = $this->_customerSession->getCustomer();
        $result = null;
        if ($customer !== null) {
            $result = $customer->getData('customer_coins');
        }
        return $result;
    }

    /**
     * @return bool
     */
    private function _getCanActiveValidator(){

        $grandtotal = $this->_cart->getQuote()->getGrandTotal();
        if (floatval($this->_getCustomerCoinsAmount())>=$grandtotal)
            return true;
        
        return false;
    }
}