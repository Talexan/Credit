<?php 

    namespace Talexant\Credit\Controller\Customer;  

    use Magento\Framework\View\Result\PageFactory;

    class Coin extends \Magento\Framework\App\Action\Action { 
        
        public function execute() { 
            $this->_view->loadLayout(); 
            $this->_view->renderLayout(); 
            //  return Magento\Framework\View\Result\PageFactory::$this->resultPageFactory->create();
    } 
} 