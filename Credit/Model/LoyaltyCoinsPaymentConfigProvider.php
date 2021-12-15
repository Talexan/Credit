<?php

namespace Talexan\Credit\Model;

/**
 * @deprecated
 */
class LoyaltyCoinsPaymentConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    public function getConfig()
    {   // window.checkoutConfig.payment
        return [
            // 'key' => 'value' pairs of configuration
            /* 'payment' => [
                'loyaltyCreditCoins' => [
                    'amountCoins' => $this->getAmountCoins()
                ]] */
        ];
    }

    /* private function getAmountCoins ()
    {
        // Монеты пользователя - это не параметры 
        // конфигурации!? 
        return 0.0;
    } */
}
