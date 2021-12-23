<?php

namespace Talexan\Credit\Observer;

use Talexan\Credit\Observer\LoyaltyCoinsAccrualAndHistoryAccruals as Observer;
use Talexan\Credit\Model\Coin;

class LoyaltyCoinsAdminSave extends Observer
{

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

        $this->setLoyaltyCreditCoinsInCustomAttribute($customerId, $coins);
        $this->setHistoryLoyaltyCreditCoins($customerId, $coins, Coin::TYPE_SET_ADMIN);
    }
}
