<?php

namespace Talexan\Credit\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Talexan\Credit\Model\Coin;
use Talexan\Credit\Model\ResourceModel\Coin as CoinsResourceModel;

class LoyaltyCoinsAccrualAndHistoryAccruals implements ObserverInterface
{
    //const CUSTOMER_ATTRIBUTE_CODE = 'customer_coins';
    const CUSTOMER_ATTRIBUTE_CODE = \Talexan\Credit\Setup\Patch\Data\CustomerCoins::CUSTOMER_ATTRIBUTE_CODE;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var Talexan\Credit\Model\CoinFactory
     */
    protected $coinFactory;

    /**
     * @var \Talexan\Credit\Helper\Data
     */
    protected $helper;

    /**
     * @var CoinsResourceModel
     */
    protected $coinResourceModel;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Talexan\Credit\Model\CoinFactory $coinFactory
     * @param \Talexan\Credit\Helper\Data $helper
     * @param CoinsResourceModel $coinResourceModel
     * @param LoggerInterface $logger
     */
    public function __construct(
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Talexan\Credit\Model\CoinFactory $coinFactory,
        \Talexan\Credit\Helper\Data $helper,
        CoinsResourceModel $coinResourceModel,
        LoggerInterface $logger
    ) {
        $this->customerRepository = $customerRepository;
        $this->coinFactory = $coinFactory;
        $this->helper = $helper;
        $this->coinResourceModel = $coinResourceModel;
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
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
            $creditCoins = ($quote->getPayment()->getMethod() ==
                \Talexan\Credit\Model\Method\LoyaltyCoin::PAYMENT_METHOD_CODE) ?
                -$quote->getSubtotal() : $this->helper->calculateReceivedCoins($quote->getSubtotal());

            $this->setLoyaltyCreditCoinsInCustomAttribute($quote, $creditCoins);
            $this->setHistoryLoyaltyCreditCoins($quote, $creditCoins);
        } catch (\Exception $e) {

            // лог ошибки...
            /** @var Psr\Log\LoggerInterface $this */
            $this->logger->err($e->getMessage());
            return;
        }
    }

    /**
     * @var void
     * @return bool
     */
    protected function isCreditEnable()
    {
        return (bool)$this->helper->getGeneralConfig('enabled');
    }

    /**
     * write coins into History Loyalty Credit Coins table
     * @param \Magento\Quote\Model\Quote $quote
     * @param  float $creditCoins
     * @return void
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function setHistoryLoyaltyCreditCoins(\Magento\Quote\Model\Quote $quote, float  $creditCoins)
    {
        $customerId = $quote->getCustomerId();
        $history = $this->coinFactory->create();
        $history->setData('customer_id', $customerId)
                ->setData('coins_received', $creditCoins)
                ->setData('occasion', Coin::TYPE_PURCHASE_PRODUCT);
        $this->coinResourceModel->save($history);
    }

    /**
     * write coins into customer custom attribute
     * @param \Magento\Quote\Model\Quote $quote
     * @param  float $creditCoins
     * @return void
     * @throws \Exception
     */
    public function setLoyaltyCreditCoinsInCustomAttribute(\Magento\Quote\Model\Quote $quote, float  $creditCoins)
    {
        $customerId = $quote->getCustomerId();

        /** @var \Magento\Customer\Model\Data\Customer */
        $customerData = $this->customerRepository->getById($customerId);
        $oldCreditCoins = $customerData
             ->getCustomAttribute(self::CUSTOMER_ATTRIBUTE_CODE)
             ->getValue();

        if (($oldCreditCoins + $creditCoins) < 0) {
            throw new \Exception('The customer does not have enough coins in the account');
        }

        $customerData->setCustomAttribute(
            self::CUSTOMER_ATTRIBUTE_CODE,
            $oldCreditCoins + $creditCoins
        );
        $this->customerRepository->save($customerData);
    }
}
