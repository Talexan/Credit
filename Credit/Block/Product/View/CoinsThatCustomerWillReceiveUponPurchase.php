<?php

namespace Talexan\Credit\Block\Product\View;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Http\Context as HttpContext;

class CoinsThatCustomerWillReceiveUponPurchase extends \Magento\Catalog\Block\Product\View
{

    /**
     * @var \Talexan\Credit\Helper\Data $loyaltyData
     */
    protected $loyaltyData;

    /**
     * @var HttpContext $httpContext,
     */
    protected $httpContext;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param \Magento\Customer\Model\Session $customerSession
     * @param ProductRepositoryInterface|\Magento\Framework\Pricing\PriceCurrencyInterface $productRepository
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Talexan\Credit\Helper\Data $loyaltyData ,
     * @param HttpContext $httpContext ,
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
        \Talexan\Credit\Helper\Data $loyaltyData,
        HttpContext $httpContext,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );

        $this->loyaltyData = $loyaltyData;
        $this->httpContext = $httpContext;
    }

    /**
     * Customer is logged in
     * @return bool
     */
    public function getCustomerIsLoggedIn()
    {
        return (bool)$this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }

    /**
     * retrieve coins received upon purchase
     * @return float
     */
    public function getReceivedCoin()
    {
        return $this->loyaltyData->calculateReceivedCoins($this->getProduct()->getFinalPrice());
    }
}
