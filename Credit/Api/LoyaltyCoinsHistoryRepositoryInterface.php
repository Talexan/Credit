<?php

namespace Talexan\Credit\Api;


/**
 * Loyalty coins CRUD interface.
 * @api
 * @since 100.*.*
 */
interface LoyaltyCoinsHistoryRepositoryInterface
{
    /**
     *
     * @param \Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterface $coinsHistory
     * @return \Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterface
     */
    public function save(\Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterface $coinsHistory);

    /**
     * Retrieve customer coins history.
     *
     * @param $id
     * @return \Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterface
     */
    public function get($id);

    /**
     * Get record by ENTITY_ID.
     *
     * @param $entityId
     * @return \Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterface
     */
    public function getById($entityId);

    /**
     * Retrieve list coins history which match a specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Customer\Api\Data\CustomerSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete history item.
     *
     * @param \Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterface $coinsHistory
     * @return bool true on success
     */
    public function delete(\Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterface $coinsHistory);

    /**
     * Delete history item by entity_id.
     *
     * @param int $entityId
     * @return bool true on success
     */
    public function deleteById($entityId);
}
