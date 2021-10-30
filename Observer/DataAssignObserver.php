<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Talexan\Credit\Observer;

use Magento\Framework\Event\ObserverInterface;
use \Magento\Customer\Api\CustomerRepositoryInterface;
use Talexan\Credit\Model\CoinFactory;
use Talexan\Credit\Model\Coin;


class DataAssignObserver implements ObserverInterface
{
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
     * Constructor
     *
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Sales\Model\ResourceModel\Order\Collection $collection
     * @param Talexan\Credit\Model\CoinFactory $coinFactory
     */
    public function __construct(
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Sales\Model\ResourceModel\Order\Collection $collection,
        \Talexan\Credit\Model\CoinFactory $coinFactory
    ) {
        $this->_customerRepository = $customerRepository;
        $this->_collection = $collection;
        
    }

    /**
     * 
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
       
        $orderIds = $observer->getEvent()->getData('order')->getOrderIds();
        if (!$orderIds || !is_array($orderIds)) {
            return $this;
        }
        
        $this->_collection->addFieldToFilter('entity_id', ['in' => $orderIds]);

        foreach ($this->_collection as $order) {
            /** @var $order \Magento\Sales\Api\Data\OrderInterface */

            $additionalData[] = unserialize($order->getAdditionalData());

            if(isset($additionalData[\Talexan\Credit\Plugin\Payment::CREDIT_COINS_ENABLED]) 
                && ($additionalData[\Talexan\Credit\Plugin\Payment::CREDIT_COINS_ENABLED] == 1)){
                   
                    $creditCoin = $order->getGrandTotal() * 
                    $additionalData[\Talexan\Credit\Plugin\Payment::CREDIT_COINS_PERCENT_PURCHASE];

                    //////// setAttributeCredit ////////
                    $customer = $this->_customerRepository->getById(
                            $order->getCustomerId());
                    $customer->setData('customer_coins', $customer->getData('customer_coins')
                                        + $creditCoin);
                    $customer->save();

                    //////// setHistoryCredit /////////
                    $creditHistory = $this->_coinFactory->create();
                    $creditHistory->addData([
                                                'customer_id' => $order->getCustomerId(),
                                                'occasion'    => $order->getRealOrderId(),
                                                'amount_purchase' => $order->getGrandTotal(),
                                                'coins_received' => $creditCoin,
                                                'created_at' => $order->getCreatedAt()
                                            ]);
                    $creditHistory->save();
                }
        }
       
        return $this;
    }
}
