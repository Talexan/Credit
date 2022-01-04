<?php

namespace Talexan\Credit\Model\ResourceModel;

use Magento\Framework\ObjectManagerInterface;
use Talexan\Credit\Api\Data\CoinsHistorySearchResultInterface;
use Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterface;
use Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterfaceFactory; //Factory?
use Talexan\Credit\Model\CoinFactory;
use Talexan\Credit\Model\ResourceModel\CoinFactory as CoinsResourceModelFactory;

class LoyaltyCoinsHistoryRepository implements \Talexan\Credit\Api\LoyaltyCoinsHistoryRepositoryInterface
{
    /**
     * @var CoinFactory
     */
    protected $coinsFactory;

    /**
     * @var CoinsResourceModelFactory
     */
    protected $resourceCoinsFactory;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var LoyaltyCoinsHistoryInterfaceFactory
     */
    protected $coinsHistoryFactory;

    /**
     * @var CoinsHistorySearchResultInterface
     */
    protected $searchResult; //Factory;

    /**
     * LoyaltyCoinsHistoryRepository constructor.
     * @param CoinFactory $coinFactory
     * @param \Talexan\Credit\Model\ResourceModel\CoinFactory $resourceCoinsFactory
     * @param LoyaltyCoinsHistoryInterfaceFactory $coinsHistoryFactory
     * @param CoinsHistorySearchResultInterface $searchResult
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        CoinFactory $coinFactory,
        CoinsResourceModelFactory $resourceCoinsFactory,
        LoyaltyCoinsHistoryInterfaceFactory $coinsHistoryFactory,
        \Talexan\Credit\Model\CoinsHistorySearchResult $searchResult,
        ObjectManagerInterface $objectManager
    ) {
        $this->coinsFactory = $coinFactory;
        $this->resourceCoinsFactory = $resourceCoinsFactory;
        $this->coinsHistoryFactory = $coinsHistoryFactory;
        $this->searchResult = $searchResult;
        $this->objectManager = $objectManager;
    }

    /**
     *
     * @param \Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterface $coinsHistory
     * @return \Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function save(\Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterface $coinsHistory)
    {
        $coinsModel = $this->coinsFactory->create();
        $coinsModel->setData($coinsHistory->__toArray());
        $this->resourceCoinsFactory->create()->save($coinsModel);
        // todo
        return $coinsHistory;
    }

    /**
     *
     * @param $customerId
     * @return \Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterface
     */
    public function get($id)
    {
        return $this->getById($id);
    }

    /**
     * Get record by ENTITY_ID.
     *
     * @param $entityId
     * @return \Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterface
     */
    public function getById($entityId)
    {
        /** @var  \Talexan\Credit\Model\ResourceModel\Coin */
        $resource = $this->resourceCoinsFactory->create();
        /** @var  \Talexan\Credit\Model\Coin $model */
        $model = $this->coinsFactory->create();
        $resource->load($model, $entityId);
        $data = $model->toArray([
            LoyaltyCoinsHistoryInterface::ENTITY_ID,
            LoyaltyCoinsHistoryInterface::CUSTOMER_ID,
            LoyaltyCoinsHistoryInterface::COINS_RECEIVED,
            LoyaltyCoinsHistoryInterface::OCCASION,
            LoyaltyCoinsHistoryInterface::CREATED_AT
        ]);
        return $this->coinsHistoryFactory->create($data);
    }

    /**
     * Retrieve list coins history which match a specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Talexan\Credit\Api\Data\CoinsHistorySearchResultInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $searchResults = $this->searchResult; //Factory->create();

        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \Talexan\Credit\Model\ResourceModel\Coin\Collection $collection */
        $collection = $this->coinsFactory->create()->getCollection();

        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }

        $searchResults->setTotalCount($collection->getSize());
        $sortOrdersData = $searchCriteria->getSortOrders();

        if ($sortOrdersData) {
            foreach ($sortOrdersData as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    $sortOrder->getDirection()
                );
            }
        }

        if ($searchCriteria->getCurrentPage() && $searchCriteria->getPageSize()) {
            $collection->setCurPage($searchCriteria->getCurrentPage());
            $collection->setPageSize($searchCriteria->getPageSize());
        }

        $searchItems = [];

        foreach ($collection as $item) {
            $searchItem = $this->coinsHistoryFactory->create();

            foreach ($item->getData() as $key => $value) {
                $searchItem->setData($key, $value);
            }

            $searchItems[] = $searchItem;
        }

        $searchResults->setItems($searchItems);

        return $searchResults;
    }

    /**
     * Delete history item.
     *
     * @param \Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterface $coinsHistory
     * @return bool true on success
     * @throws \Exception
     */
    public function delete(\Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterface $coinsHistory)
    {
        return $this->deleteById($coinsHistory->getId());
    }

    /**
     * Delete history item by entity_id.
     *
     * @param int $entityId
     * @return bool true on success
     * @throws \Exception
     */
    public function deleteById($entityId)
    {
        $coinsHistory = $this->coinsFactory->create()->load($entityId);

        if ($coinsHistory->getId()) {
            $coinsHistory->delete();
        }

        return true;
    }
}
