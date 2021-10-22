<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Talexan\Credit\Controller\Adminhtml\Customer;
 
class Coin extends \Magento\Customer\Controller\Adminhtml\Index
{
    /**
     * Customer compare grid
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
       
        $this->initCurrentCustomer();
//        $resultLayout = $this->resultLayoutFactory->create();
//        return $resultLayout;
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }
 
 
}