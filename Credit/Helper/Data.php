<?php

namespace Talexan\Credit\Helper;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterfaceFactory;
use Talexan\Credit\Api\LoyaltyCoinsHistoryRepositoryInterface;
use Talexan\Credit\Model\Coin;
use Talexan\Credit\Model\CoinFactory;
use Talexan\Credit\Model\ResourceModel\Coin as CoinsResourceModel;

/**
 * Class Data
 * @package Talexan\Credit\Helper
 */
class Data extends AbstractHelper
{
    const XML_PATH_LOAYLTY_PROGRAM = 'loyalty_programm/';

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var LoyaltyCoinsHistoryInterfaceFactory
     */
    protected $coinsHistoryFactory;

    /**
     * @var LoyaltyCoinsHistoryRepositoryInterface
     */
    protected $coinsHistoryRepository;

    /**
     * Data constructor.
     * @param Context $context
     * @param CoinFactory $coinFactory
     * @param CoinsResourceModel $coinResourceModel
     * @param CustomerRepositoryInterface $customerRepository
     * @param LoyaltyCoinsHistoryInterfaceFactory $coinsHistoryFactory
     * @param LoyaltyCoinsHistoryRepositoryInterface $coinsHistoryRepository
     */
    public function __construct(
        Context $context,
        CustomerRepositoryInterface $customerRepository,
        LoyaltyCoinsHistoryInterfaceFactory $coinsHistoryFactory,
        LoyaltyCoinsHistoryRepositoryInterface $coinsHistoryRepository
    ) {
        $this->customerRepository = $customerRepository;

        $this->coinsHistoryFactory = $coinsHistoryFactory;
        $this->coinsHistoryRepository = $coinsHistoryRepository;

        parent::__construct($context);
    }

    /**
     * @param $field
     * @param null $storeId
     * @return mixed
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param $code
     * @param null $storeId
     * @return mixed
     */
    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_LOAYLTY_PROGRAM . 'general/' . $code, $storeId);
    }

    /**
     * Calculate received coins
     * @param float $price
     * @return float|int
     */
    public function calculateReceivedCoins($price)
    {
        return $this->getGeneralConfig('percent_purchase') * $price /100;
    }

    /**
     * write coins into History Loyalty Credit Coins table
     * @param int $customerId
     * @param  float $creditCoins
     * @param int $occasion
     * @return void
     */
    public function setHistoryLoyaltyCreditCoins(int $customerId, float  $creditCoins, $occasion = Coin::TYPE_PURCHASE_PRODUCT)
    {
        $coinsHistory = $this->coinsHistoryFactory->create();
        $coinsHistory->setCustomerId($customerId)->setCoinsReceived($creditCoins)->setOccasion($occasion);
        $this->coinsHistoryRepository->save($coinsHistory);
    }

    /**
     * write coins into customer custom attribute
     * @param int $customerId
     * @param  float $creditCoins
     * @return void
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     */
    public function setLoyaltyCreditCoinsInCustomAttribute(int $customerId, float  $creditCoins)
    {
        /** @var \Magento\Customer\Model\Data\Customer $customerData */
        $customerData = $this->customerRepository->getById($customerId);
        $oldCreditCoins = $customerData
            ->getCustomAttribute(\Talexan\Credit\Setup\Patch\Data\CustomerCoins::CUSTOMER_ATTRIBUTE_CODE)
            ->getValue();

        if (($oldCreditCoins + $creditCoins) < 0) {
            throw new \Exception('The customer does not have enough coins in the account');
        }

        $customerData->setCustomAttribute(
            \Talexan\Credit\Setup\Patch\Data\CustomerCoins::CUSTOMER_ATTRIBUTE_CODE,
            $oldCreditCoins + $creditCoins
        );
        $this->customerRepository->save($customerData);
    }
}
