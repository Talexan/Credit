<?php
namespace Talexan\Credit\Plugin;

use \Talexan\Credit\Helper\LoyaltyData;

class Payment
{
    const CREDIT_COINS_ENABLED = "credit_coins_enabled";
    const CREDIT_COINS_PERCENT_PURCHASE= "credit_coins_percent_purchase";

    /**
     * @var \Talexan\Credit\Helper\LoyaltyData $paymentHelper
     */
    private $_paymentHelper;

    public function __construct(
        \Talexan\Credit\Helper\LoyaltyData $paymentHelper
    )
    {
        $this->_paymentHelper = $paymentHelper;
    }

    /**
     *
     * @param \Magento\Quote\Model\Quote\Payment $subject
     * @param array $additionalData
     * @return array
     */
    public function beforeSetAdditionalData(
        \Magento\Quote\Model\Quote\Payment $subject,
        $additionalData
    ) {

        return $additionalData;
    }
}