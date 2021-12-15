<?php
namespace Talexan\Credit\Model\Payment\Method;

class LoyaltyCoin extends \Magento\Payment\Model\Method\AbstractMethod
{
    const PAYMENT_METHOD_CODE = 'loyaltycoin';

    /**
     * Payment code name
     *
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD_CODE;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canOrder = true;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canCapture = true;

    /**
     * customer id
     *
     * @var int|null
     */
    private $_customerId;

    /**
     * Order paymentmethod
     *
     * @param \Magento\Framework\DataObject|InfoInterface $payment
     * @param float $amount
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     * @api
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @deprecated 100.2.0
     */
    public function order(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        return parent::order($payment, $amount);
    }

    /**
     * Capture payment method
     *
     * @param \Magento\Framework\DataObject|InfoInterface $payment
     * @param float $amount
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     * @api
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @deprecated 100.2.0
     */
    public function capture(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
       return parent::capture($payment, $amount);
    }

    /**
     * Check whether payment method can be used
     *
     * @param \Magento\Quote\Api\Data\CartInterface|null $quote
     * @return bool
     * @deprecated 100.2.0
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        /** True for guest customers, false for logged in customers
        * @return bool|null*/
        if (($quote)? $quote->getCustomerIsGuest(): false)
            return false;

        if ($quote){
            /** @var \Magento\Customer\Api\Data\CustomerInterface 
            * Information about the customer who is assigned to the cart. */ 
            $customer = $quote->getCustomer();
        
            /** Get customer id @return int|null */
            $this->_customerId = $customer->getId();
        }
        
        
        return parent::isAvailable($quote);
    }
}
