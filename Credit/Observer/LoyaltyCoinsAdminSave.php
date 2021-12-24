<?php

namespace Talexan\Credit\Observer;

use Magento\Framework\Event\ObserverInterface;
use Talexan\Credit\Model\Coin;
use Talexan\Credit\Helper\Data;

class LoyaltyCoinsAdminSave implements ObserverInterface
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * Constructor
     * @param \Talexan\Credit\Helper\Data $helper
     * @param LoggerInterface $logger
     */
    public function __construct(
        Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $data = $observer->getEvent()->getRequest()->getParams();
        $customerId = $data['customer_id'];
        $coins = $data['change_coins'];

        $this->helper->setLoyaltyCreditCoinsInCustomAttribute($customerId, $coins);
        $this->helper->setHistoryLoyaltyCreditCoins($customerId, $coins, Coin::TYPE_SET_ADMIN);
    }
}
