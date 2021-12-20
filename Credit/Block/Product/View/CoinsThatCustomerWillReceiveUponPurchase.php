<?php

namespace Talexan\Credit\Block\Product\View;

use Magento\Catalog\Api\ProductRepositoryInterface;
use \Magento\Framework\App\Http\Context as HttpContext;

class CoinsThatCustomerWillReceiveUponPurchase extends \Magento\Catalog\Block\Product\View {

    /**
     * @var \Talexan\Credit\Helper\LoyaltyData $loyaltyData
     */
    private $loyaltyData;

    /**
     * @var HttpContext $httpContext,
     */
    protected $_httpContext;
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
     * @param HttpContext $httpContext,
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
        HttpContext $httpContext,
        array $data = []
    ) {
        parent::__construct($context, $urlEncoder, $jsonEncoder, $string, $productHelper,
                            $productTypeConfig, $localeFormat, $customerSession, $productRepository,
                            $priceCurrency, $data);

        $this->loyaltyData = $loyaltyData;
        $this->_httpContext = $httpContext;            
    }

    /**
     * Активна ли программа
     * @return bool
     */
    public function isLoyaltyCoinProgrammActive()
    {
        return (bool)$this->loyaltyData->getGeneralConfig('enabled');
    }

    /**
     * Зарегистрирован ли пользователь
     * @return bool
     */
    public function customerSessionIsLoggedIn()
    {
        /** Не работает из-за кеширования страниц товара!!!
         *  Мы не можем получить какие-либо данные из клиентского сеанса, 
         *  когда кеш включен, потому что, как только начнется генерация 
         *  макета, клиентский сеанс будет очищен 
         *  \Magento\PageCache\Model\Layout\DepersonalizePlugin::afterGenerateXml
         *  на всех кешируемых страницах. Таким образом, мы не можем получить 
         *  данные о сеансах клиентов из \Magento\Customer\Model\Session.
         */
        $result = (bool)$this->customerSession->isLoggedIn();
        if ($result)
            return true;
    
        return $result;
    }


    /**
     * Customer is logged in
     * @return bool
     */
    public function getCustomerIsLoggedIn()
    {
        return (bool)$this->_httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }

    /**
     * retrieve coins received upon purchase
     * @return float
     */
    public function getReceivedCoin(){

        $receivedCoin = $this->getProduct()->getFinalPrice()*$this->loyaltyData->getGeneralConfig('percent_purchase')/100; 
        
        return $receivedCoin;
    }

}