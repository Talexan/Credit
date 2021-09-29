<?php

namespace Talexan\Attributes\Block;

class CertificatesUa extends \Magento\Catalog\Block\Product\View {

    /**
     * retrieve custom attribute certificates_ukr
     * @return string html
     */
    public function getCertificates(){

        $resultHtml = "<div class=\"product attribute certificates\" style=\"display: none;\">
                       </div>";

        $product = $this->getProduct();
        $isMyAttShow = $product->getData('is_enabled_my_att_show');

        if ($isMyAttShow){
            $certificatesAttr = $product->getData( 'certificates_ukr');
            $resultHtml = "<div class=\"product attribute certificates\" style=\"display: visible;\">
                           <strong class=\"type\">Certificates:</strong>
                           <div class=\"value\" itemprop=\"certificates\">
                            $certificatesAttr</div>
                           </div>";
            }
        
        return $resultHtml;
    }

}