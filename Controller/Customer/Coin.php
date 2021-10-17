<?php 

    namespace Talexant\Credit\Controller\Customer;  

    use Magento\Framework\View\Result\PageFactory;

    class Coin extends \Magento\Framework\App\Action\Action { 
        
        /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

        public function execute() { 
        //    $this->_view->loadLayout(); 
        //    $this->_view->renderLayout(); 
        
            $resultPage = $this->resultPageFactory->create();
            $block = $resultPage->getLayout()->getBlock('customer-coins-tab');
            if ($block) {
                $block->setRefererUrl($this->_redirect->getRefererUrl());
            }
            
            return $resultPage;
    } 
} 