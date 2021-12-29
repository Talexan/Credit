<?php

namespace Talexan\Credit\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

/**
 * Class LoyaltyCoinsAccrualAndHistoryAccruals
 * @package Talexan\Credit\Observer
 */
class LoyaltyCoinsAccrualAndHistoryAccruals implements ObserverInterface
{
    /**
     * @var \Talexan\Credit\Helper\Data
     */
    protected $helper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     * @param \Talexan\Credit\Helper\Data $helper
     * @param LoggerInterface $logger
     */
    public function __construct(
        \Talexan\Credit\Helper\Data $helper,
        LoggerInterface $logger
    ) {
        $this->helper = $helper;
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->isCreditEnable()) {
            return;
        }

        if ($observer->getEvent()->getQuote()->getCustomerIsGuest()) {
            return;
        }

        $quote = $observer->getEvent()->getQuote();
        $customerId = $quote->getCustomerId();

        try {
            $creditCoins = ($quote->getPayment()->getMethod() ==
                \Talexan\Credit\Model\Method\LoyaltyCoin::PAYMENT_METHOD_CODE) ?
                -$quote->getGrandTotal() : $this->helper->calculateReceivedCoins($quote->getSubtotal());

            $this->helper->setLoyaltyCreditCoinsInCustomAttribute($customerId, $creditCoins);
            $this->helper->setHistoryLoyaltyCreditCoins($customerId, $creditCoins);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
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
}
