<?php
namespace Talexan\Credit\Model\Data;

class LoyaltyCoinsHistory extends \Magento\Framework\Api\AbstractSimpleObject implements \Talexan\Credit\Api\Data\LoyaltyCoinsHistoryInterface
{
    /**
     * Get entity_id
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->_get(self::ENTITY_ID);
    }

    /**
     * Set entity_id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * Get customer id
     *
     * @return int|null
     */
    public function getCustomerId()
    {
        return $this->_get(self::CUSTOMER_ID);
    }

    /**
     * Set customer id
     *
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Get amount received coins
     *
     * @return float|null
     */
    public function getCoinsReceived()
    {
        return $this->_get(self::COINS_RECEIVED);
    }

    /**
     * Set amount received coins
     *
     * @param float $coinsReceived
     * @return $this
     */
    public function setCoinsReceived($coinsReceived)
    {
        return $this->setData(self::COINS_RECEIVED, $coinsReceived);
    }

    /**
     * Get occasion received coins
     *
     * @return int|null
     */
    public function getOccasion()
    {
        return $this->_get(self::CUSTOMER_ID);
    }

    /**
     * Set occasion received coins
     *
     * @param int $occasion
     * @return $this
     */
    public function setOccasion($occasion)
    {
        return $this->setData(self::OCCASION, $occasion);
    }

    /**
     * Get created at time
     *
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * Set created at time
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}
