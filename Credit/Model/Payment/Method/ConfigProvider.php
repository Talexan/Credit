<?php

namespace Talexan\Credit\Model\Payment\Method;

class ConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    public function getConfig()
    {   // window.checkout.payment.loyaltycoin.(customerCoinsAmount|canActive)
        return [
            // 'key' => 'value' pairs of configuration
        ];
    }
}
