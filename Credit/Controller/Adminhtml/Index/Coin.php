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
     * @param Context $context
     * @param LayoutFactory $layoutFactory
     */
    public function __construct(
        Context $context,
        LayoutFactory $layoutFactory
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
        if (!$this->getRequest()->getParam('id')) {
            return $this->_redirect($this->_redirect->getRefererUrl());
        }
        return $this->resultLayoutFactory->create();
    }
}
