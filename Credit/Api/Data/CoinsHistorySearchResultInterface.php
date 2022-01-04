<?php
namespace Talexan\Credit\Api\Data;

interface CoinsHistorySearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get items list.
     *
     * @return \Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterface[]
     */
    public function getItems();

    /**
     * Set items list.
     *
     * @param \Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
