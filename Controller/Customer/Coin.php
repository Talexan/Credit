<?php 

    namespace Talexant\Credit\Controller\Customer;  

    class Coin extends \Magento\Framework\App\Action\Action { 
        
        public function execute() { 
            $this->_view->loadLayout(); 
            $this->_view->renderLayout(); 
    } 
} 