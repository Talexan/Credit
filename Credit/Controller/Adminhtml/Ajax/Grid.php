<?php

namespace Talexan\Credit\Controller\Adminhtml\Ajax;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\View\LayoutFactory;

/**
 * Class Grid
 * @package Talexan\Credit\Controller\Adminhtml\Ajax
 */
class Grid extends \Magento\Backend\App\Action
{
    /**
     * @var RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var LayoutFactory
     */
    protected $layoutFactory;

    /**
     * Grid constructor.
     * @param Context $context
     * @param RawFactory $resultRawFactory
     * @param LayoutFactory $layoutFactory
     */
    public function __construct(
        Context $context,
        RawFactory $resultRawFactory,
        LayoutFactory $layoutFactory
    ) {
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Raw|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Raw $response */
        $response = $this->resultRawFactory->create();
        $response->setHeader('Content-type', 'text/plain');

        $layout = $this->layoutFactory->create();
        $block = $layout->createBlock('\Talexan\Credit\Block\Adminhtml\Customer\Edit\Tab\Coin\LoyaltyCoinsHistoryGrid');

        $response->setContents($block->toHtml());
        return $response;
    }
}
