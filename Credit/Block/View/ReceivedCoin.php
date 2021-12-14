<?php

namespace Talexan\Credit\Block\View;

use Magento\Catalog\Api\ProductRepositoryInterface;

class ReceivedCoin extends \Magento\Catalog\Block\Product\View {

    /**
     * @var \Talexan\Credit\Helper\LoyaltyData $loyaltyData
     */
    private $loyaltyData;

    /**
     * @param Context $context
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param \Magento\Customer\Model\Session $customerSession
     * @param ProductRepositoryInterface|\Magento\Framework\Pricing\PriceCurrencyInterface $productRepository
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Talexan\Credit\Helper\LoyaltyData $loyaltyData,
     * @param array $data
     * @codingStandardsIgnoreStart
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Talexan\Credit\Helper\LoyaltyData $loyaltyData,
        array $data = []
    ) {
        parent::__construct($context, $urlEncoder, $jsonEncoder, $string, $productHelper,
                            $productTypeConfig, $localeFormat, $customerSession, $productRepository,
                            $priceCurrency, $data);

        $this->loyaltyData = $loyaltyData;            
    }


    /**
     * retrieve coins received upon purchase
     * @return string html
     */
    public function getReceivedCoin(){

            $receivedCoin = $this->getProduct()->getPrice()*$this->loyaltyData->getGeneralConfig('percent_purchase')/100; // a little logic ?????
        
        return $receivedCoin;
    }

}