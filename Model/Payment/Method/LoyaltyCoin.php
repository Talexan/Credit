<?php
namespace Talexan\Credit\Model\Payment\Method;

use Talexan\Credit\Model\CoinFactory;
use Talexan\Credit\Model\Coin;
use Magento\Directory\Helper\Data as DirectoryHelper;

/**
 * Pay In Store payment method model
 */
class LoyaltyCoin extends \Magento\Payment\Model\Method\AbstractMethod
{

    const CODE = 'loyaltycoin';

    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = self::CODE;

    /**
     * @var Talexan\Credit\Model\CoinFactory $coinFactory
     */
    private $_coinFactory;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Payment\Model\Method\Logger $logger
     * @param Talexan\Credit\Model\CoinFactory $coinFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     * @param DirectoryHelper $directory
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Talexan\Credit\Model\CoinFactory $coinFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [],
        DirectoryHelper $directory = null
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $resource,
            $resourceCollection,
            $data,
            $directory
        );

        $this->_coinFactory = $coinFactory;
    }


    /**
     * @inheritdoc
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
       $this->setData('quote', $quote);
       return parent::isAvailable($quote) && ($this->_getAmountCoins($quote) >= $quote->getGrandTotal());
    }

    /**
     * Сhecks if there are enough credit coins
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return float|null
     */
    private function _getAmountCoins(\Magento\Quote\Api\Data\CartInterface $quote){
        if($quote){
           return floatval($quote->getCustomer()->getData('customer_coins'));
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function capture(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $payment->getOrder();

        try{
            //////// setAttributeCredit ////////
            if ($this->getData('quote')){
                $customer = $this->getData('quote')->getCustomer();
                $customer->setData('customer_coins', $customer->getData('customer_coins')-$amount);
                $customer->save();
            }

            //////// setHistoryCredit /////////
            $creditHistory = $this->_coinFactory->create();
            $creditHistory->addData([
                'customer_id' => $order->getCustomerId(),
                'occasion'    => $order->getRealOrderId(),
                'amount_purchase' => $amount,
                'coins_received' => -$amount,
                'created_at' => $order->getCreatedAt()
            ]);
            $creditHistory->save();

            $payment->setTransactionId($order->getRealOrderId())
                ->setIsTransactionClosed(0);
 
        } catch (\Exception $e) {
            $this->debugData(['exception' => $e->getMessage()]);
            $this->_logger->error(__('Payment capturing error.'));
            throw new \Magento\Framework\Validator\Exception(__('Payment capturing error.'));
        }

        return $this;
    }

}