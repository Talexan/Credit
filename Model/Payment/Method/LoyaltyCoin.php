<?php
namespace Talexan\Credit\Model\Payment\Method;

/**
 * Pay In Store payment method model
 */
class LoyaltyCoin extends \Magento\Payment\Model\Method\AbstractMethod
{

    const CODE = 'loyaltycoin';

    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = self::CODE;
}