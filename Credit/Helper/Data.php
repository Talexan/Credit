<?php

namespace Talexan\Credit\Helper;

use Magento\CatalogImportExport\Model\Import\Proxy\Product\ResourceModel;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Talexan\Credit\Model\Coin;
use Talexan\Credit\Model\CoinFactory;
use Talexan\Credit\Model\ResourceModel\Coin as CoinsResourceModel;

/**
 * Class Data
 * @package Talexan\Credit\Helper
 */
class Data extends AbstractHelper
{
    /**
     * $this->helperData->getGeneralConfig('enable');
     */

    const XML_PATH_LOAYLTY_PROGRAM = 'loyalty_programm/';

    /**
     * @var Coin
     */
    protected $coinFactory;

    /**
     * @var CoinsResourceModel
     */
    protected $coinResourceModel;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * Data constructor.
     * @param Context $context
     * @param CoinFactory $coinFactory
     * @param ResourceModel $coinResourceModel
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        Context $context,
        CoinFactory $coinFactory,
        ResourceModel $coinResourceModel,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->coinFactory = $coinFactory;
        $this->coinResourceModel = $coinResourceModel;
        $this->customerRepository = $customerRepository;

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
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function setHistoryLoyaltyCreditCoins(int $customerId, float  $creditCoins, $occasion = Coin::TYPE_PURCHASE_PRODUCT)
    {
        $history = $this->coinFactory->create();
        $history->setData('customer_id', $customerId)
            ->setData('coins_received', $creditCoins)
            ->setData('occasion', $occasion);
        $this->coinResourceModel->save($history);
    }
    //todo Move to helper

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
