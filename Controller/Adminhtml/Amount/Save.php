<?php
namespace Talexan\Credit\Controller\Adminhtml\Amount;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action;
use Talexan\Credit\Model\CoinFactory as ModelFactory;
use \Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Save User action.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Save extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    /*
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    //const ADMIN_RESOURCE = 'Talexan_UsersList::acl_save';

    /**
     * @var \Talexan\Credit\Model\CoinFactory
     */
    protected $modelFactory;

     /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
     */
    private $customerRepositoryInterface;


    /**
     * @param Action\Context $context
     * @param ModelFactory
     * @param Repository $repository
     */
    public function __construct(
        Action\Context $context,
        ModelFactory $modelFactory,
        CustomerRepositoryInterface $customerRepositoryInterface
    ) {
        $this->modelFactory = $modelFactory;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            
            /** @var \Talexan\UsersList\Model\Users $model */
            $model = $this->modelFactory->create();

            $customerId = $this->getRequest()->getParam('customer_id');
            if ($customerId) {
                try {
                    $customer = $this->customerRepositoryInterface->getById($customerId);
                    $customerCoin = $customer->getData('customer_coins');
                    $customer->setData('customer_coins', $customerCoin + $data['input_coins']);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('Sorry, I can`t complete the transaction with the coin.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }
            else
            {
                $this->messageManager->addErrorMessage(__('Sorry, something went wrong: customer id.'));
                throw new LocalizedException(__('Sorry, something went wrong: customer id.'));
            }

            $historyData = ['customer_id' => $customerId, 
                            'occasion' => 'Added by admin',
                            'coins_received' =>$data['input_coins']];
            $model->setData($historyData);

            try {
                $model->save();
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
            } catch (\Throwable $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the coins.'));
            }
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     *  @return bool
     */
    protected function _isAllowed()
    {
         return true; //$this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }
}