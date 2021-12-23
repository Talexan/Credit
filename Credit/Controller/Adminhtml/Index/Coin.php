<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Talexan\Credit\Controller\Adminhtml\Index;

class Coin extends \Magento\Customer\Controller\Adminhtml\Index
{
    /**
     * Customer grid
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        /** Customer initialization ???*/
        $this->initCurrentCustomer();

        return $this->resultLayoutFactory->create();
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
