<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Talexan\Credit\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\LayoutFactory;

class Coin extends \Magento\Backend\App\Action
{
    /**
     * @var LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @param Context $context
     * @param LayoutFactory $layoutFactory
     */
    public function __construct(
        Context $context,
        LayoutFactory $layoutFactory
        //RedirectFactory $redirectFactory
    ) {
        $this->resultLayoutFactory = $layoutFactory;

        parent::__construct($context);
    }
    /**
     * Customer loyalty credit coins history grid
     *
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        /** Customer initialization ???*/
        //$this->initCurrentCustomer();

        if (!$this->getRequest()->getParam('id')) {
            return $this->_redirect($this->_redirect->getRefererUrl());
        }
        return $this->resultLayoutFactory->create();
    }

    /**
     * Customer grid
     *
     * @return \Magento\Framework\View\Result\Page
     */
    /*public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        return $this->execute();
    }*/
}
