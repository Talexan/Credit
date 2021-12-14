<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Talexan\Credit\Controller\Adminhtml\Customer;
 
class Coin extends \Magento\Customer\Controller\Adminhtml\Index
{
    /**
     * Customer grid
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
       
        $this->initCurrentCustomer();
        $resultLayout = $this->resultLayoutFactory->create();
        return $resultLayout;
    }

    /**
     * Customer grid
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        return $this->execute();
    }
}