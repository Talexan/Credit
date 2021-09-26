<?php

namespace Talexan\Attributes\Block;

class CertificatesUa extends \Magento\Catalog\Block\Product\View {

    /**
     * @var \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @inheritdoc
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
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        array $data = []
    )
    {
        parent::__construct($context, $urlEncoder, $jsonEncoder, $string, $productHelper,
                    $productTypeConfig, $localeFormat, $customerSession, $productRepository,
                    $priceCurrency, $data);
        
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * retrieve value attribute certificates_ukr
     * @return string html
     */
    public function getCertificates(){

        $resultHtml = "<div class=\"product attribute certificates\" style=\"display: none;\">
                       </div>";

        $eavSetup = $this->eavSetupFactory->create();
        $isMyAttShow = $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY,
        'is_enabled_my_att_show', "value");
        if ($isMyAttShow){
            $certificatesAttr = $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY,
            'certificates_ukr', "value");

            if (isset(certificatesAttr)){
                $resultHtml = "<div class=\"product attribute certificates\" style=\"display: visible;\">
                               <strong class=\"type\">Certificates:</strong>
                               <div class=\"value\" itemprop=\"certificates\">
                               $isMyAttShow</div>
                               </div>";
            }

        }
        
        return $resultHtml;
    }

}