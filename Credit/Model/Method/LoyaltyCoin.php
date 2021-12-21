<?php
namespace Talexan\Credit\Model\Method;

class LoyaltyCoin extends \Magento\Payment\Model\Method\AbstractMethod
{
    const PAYMENT_METHOD_CODE = 'loyaltycoin';
    const CUSTOMER_ATTRIBUTE_CODE = \Talexan\Credit\Setup\Patch\Data\CustomerCoins::CUSTOMER_ATTRIBUTE_CODE;

    /**
     * Payment code name
     *
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD_CODE;


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
        * @var bool|null*/
        if (($quote) ? $quote->getCustomerIsGuest() : false) {
            return false;
        }

        // 2.
        /** @todo Проверить платежеспособность*/
        /** @var \Magento\Customer\Model\Data\Customer */
        $customerData = $quote->getCustomer();

        try {
            $customerLoyaltyCreditCoinsAttribute = $customerData
            ->getCustomAttribute(self::CUSTOMER_ATTRIBUTE_CODE);
            $amountCreditCoins = $customerLoyaltyCreditCoinsAttribute
            // возвращает 0, если не было setCustomAttribute() до этого?
            ->getValue();
        } catch (\Throwable $e) {
            // если что-то не так с монетами, то
            // метод не доступен!
            return false;
        }

        $grandTotal = $quote->getGrandTotal();

        // Клиент неплатежеспособен!
        if ($grandTotal > $amountCreditCoins) {
            return false;
        }

        return parent::isAvailable($quote);
    }
}
