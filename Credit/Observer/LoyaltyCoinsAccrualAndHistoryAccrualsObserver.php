<?php

namespace Talexan\Credit\Observer;

use Magento\Framework\Event\ObserverInterface;
use Talexan\Credit\Model\Coin;
use Talexan\Credit\Model\ResourceModel\Coin as ResourceModel;
use Psr\Log\LoggerInterface;

class LoyaltyCoinsAccrualAndHistoryAccrualsObserver implements ObserverInterface
{
    //const CUSTOMER_ATTRIBUTE_CODE = 'customer_coins';
    const CUSTOMER_ATTRIBUTE_CODE = \Talexan\Credit\Setup\Patch\Data\CustomerCoins::CUSTOMER_ATTRIBUTE_CODE;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Collection
     */
    protected $_collection;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $_customerRepository;

    /**
     * @var Talexan\Credit\Model\CoinFactory
     */
    protected $_coinFactory;

    /**
     * @var \Talexan\Credit\Helper\LoyaltyData
     */
    protected $_helper;

    private $coinResourceModel;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $_customerFactory;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer
     */
    protected $_customerResourceModel;

    /**
     * @var LoggerInterface
     */
    private $_logger;

    /**
     * Constructor
     *
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Sales\Model\ResourceModel\Order\Collection $collection
     * @param \Talexan\Credit\Model\CoinFactory $coinFactory
     * @param \Talexan\Credit\Helper\LoyaltyData $helper
     * @param ResourceModel $coinResourceModel
     * @param LoggerInterface $logger
     */
    public function __construct(
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Sales\Model\ResourceModel\Order\Collection $collection,
        \Talexan\Credit\Model\CoinFactory $coinFactory,
        \Talexan\Credit\Helper\LoyaltyData $helper,
        ResourceModel $coinResourceModel,
        LoggerInterface $logger
    ) {
        $this->_customerRepository = $customerRepository;
        $this->_collection = $collection;
        $this->_coinFactory = $coinFactory;
        $this->_helper = $helper;
        $this->coinResourceModel = $coinResourceModel;
        $this->_logger = $logger;
    }

    /**
     *
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return DataAssignObserver|void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // Проверить, что кредит действует
        if (!$this->isCreditEnable()) {
            return;
        }

        // 1. Проверить, что пользователь залогинился, т. е. он не гость
        if ($observer->getEvent()->getQuote()->getCustomerIsGuest()) {
            //   $this->messageManager->addErrorMessage('Please, register and you can earn on purchases');
            return;
        }

        $quote = $observer->getEvent()->getQuote();        

        try {
                if($quote->getPayment()->getMethod() == 
                   \Talexan\Credit\Model\Method\LoyaltyCoin::PAYMENT_METHOD_CODE) {
                    $creditCoins = -$quote->getSubtotal();
                }
                else {
                    $creditCoins = 0.01 * $this->getCreditPercent() * $quote->getSubtotal();
                }  
                
                $this->setLoyaltyCreditCoinsInCustomAttribute($quote, $creditCoins);
                $this->setHistoryLoyaltyCreditCoins($quote, $creditCoins);
                
        } catch (\Exception $e) {

            // лог ошибки...
            /** @var Psr\Log\LoggerInterface $this */
            $this->_logger->err($e->getMessage());
            return;
        }
    }

    /**
     * @var void
     * @return float
     */
    private function getCreditPercent()
    {
        $result = $this->_helper->getGeneralConfig('percent_purchase');
        return (float)$result;
    }

    /**
     * @var void
     * @return bool
     */
    private function isCreditEnable()
    {
        $result = $this->_helper->getGeneralConfig('enabled');
        return (bool)$result;
    }

    /**
     * write coins into History Loyalty Credit Coins table
     * @param \Magento\Quote\Model\Quote $quote
     * @param  float  $creditCoins
     * @return void
     */
    private function setHistoryLoyaltyCreditCoins(\Magento\Quote\Model\Quote $quote, float  $creditCoins)
    {
        $customerId = $quote->getCustomerId();
        $history = $this->_coinFactory->create();
        $history->setData('customer_id', $customerId)
                ->setData('coins_received', $creditCoins)
                ->setData('occasion', Coin::TYPE_PURCHASE_PRODUCT);
        $this->coinResourceModel->save($history);
    }

    /**
     * write coins into customer custom attribute
     * @param \Magento\Quote\Model\Quote $quote
     * @param  float  $creditCoins
     * @return void
     * @throws \Exeption
     */
    private function setLoyaltyCreditCoinsInCustomAttribute(\Magento\Quote\Model\Quote $quote, float  $creditCoins)
    {
         $customerId = $quote->getCustomerId();
        
         try{
             /** @var \Magento\Customer\Model\Data\Customer */
         $customerData = $this->_customerRepository->getById($customerId);
         $oldCreditCoins = $customerData
             ->getCustomAttribute(self::CUSTOMER_ATTRIBUTE_CODE)
             ->getValue();
         }
         catch (\Throwable $e){
             // может быть атрибут еще не установлен,
             // установим ниже
             $oldCreditCoins = 0;
         }
        
         if (($oldCreditCoins + $creditCoins) < 0)
            throw new \Exception('The customer does not have enough coins in the account');
         
         $customerData->setCustomAttribute(
             self::CUSTOMER_ATTRIBUTE_CODE,
             $oldCreditCoins + $creditCoins
         );
         $this->_customerRepository->save($customerData);
    }
}
