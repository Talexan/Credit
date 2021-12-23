<?php
namespace  Talexan\Credit\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Controller\Adminhtml\Index\InlineEdit as MagentoInlineEdit;
use Magento\Customer\Model\AddressRegistry;
use Talexan\Credit\Model\Coin;
use Talexan\Credit\Model\CoinFactory;
use Talexan\Credit\Model\ResourceModel\Coin as CoinsResourceModel;
use Magento\Customer\Api\Data\CustomerInterface;

class InlineEdit extends MagentoInlineEdit
{
    /**
     * @var CoinFactory
     */
    protected $coinFactory;

    /**
     * @var CoinsResourceModel
     */
    protected $coinsResource;

    /**
     * @param Action\Context $context
     * @param CustomerRepositoryInterface $customerRepository
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Customer\Model\Customer\Mapper $customerMapper
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param \Psr\Log\LoggerInterface $logger
     * @param CoinFactory $coinFactory
     * @param CoinsResourceModel $coinsResource
     * @param AddressRegistry|null $addressRegistry
     * @param \Magento\Framework\Escaper $escaper
     */
    public function __construct(
        Action\Context $context,
        CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Customer\Model\Customer\Mapper $customerMapper,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Psr\Log\LoggerInterface $logger,
        CoinFactory $coinFactory,
        CoinsResourceModel $coinsResource,
        AddressRegistry $addressRegistry = null,
        \Magento\Framework\Escaper $escaper = null
    ) {
        $this->coinFactory = $coinFactory;
        $this->coinsResource = $coinsResource;

        parent::__construct(
            $context,
            $customerRepository,
            $resultJsonFactory,
            $customerMapper,
            $dataObjectHelper,
            $logger,
            $addressRegistry,
            $escaper
        );
    }

    /**
     * Save customer with error catching
     *
     * @param CustomerInterface $customer
     * @return void
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */

    protected function saveCustomer(CustomerInterface $customer)
    {
        $customerData = $this->customerRepository->getById($customer->getId());
        $oldCreditCoins = $customerData
            ->getCustomAttribute(\Talexan\Credit\Setup\Patch\Data\CustomerCoins::CUSTOMER_ATTRIBUTE_CODE)
            ->getValue();

        parent::saveCustomer($customer);

        $newCreditCoins = $customer->getCustomAttribute(\Talexan\Credit\Setup\Patch\Data\CustomerCoins::CUSTOMER_ATTRIBUTE_CODE)
            ->getValue();

        $this->setHistoryLoyaltyCreditCoins($customer->getId(), $newCreditCoins - $oldCreditCoins);
    }

    /**
     * @param int $customerId
     * @param float $creditCoins
     * @param int $occasion
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function setHistoryLoyaltyCreditCoins(int $customerId, float  $creditCoins, $occasion = Coin::TYPE_SET_ADMIN)
    {
        $history = $this->coinFactory->create();
        $history->setData('customer_id', $customerId)
            ->setData('coins_received', $creditCoins)
            ->setData('occasion', $occasion);
        $this->coinsResource->save($history);
    }
}
