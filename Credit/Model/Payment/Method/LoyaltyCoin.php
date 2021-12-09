<?php
namespace Talexan\Credit\Model\Payment\Method;

class LoyaltyCoin extends \Magento\Payment\Model\Method\AbstractMethod
{
    const PAYMENT_METHOD_FREE_CODE = 'loyaltycoin';

    /**
     * Payment code name
     *
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD_FREE_CODE;
}
