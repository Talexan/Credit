<?php

namespace  Talexan\Credit\Api\Data;


/**
 * Customer loyalty coins history interface for API handling.
 *
 * @api
 * @since 100.*.*
 */
interface LoyaltyCoinsHistoryInterface
{
    /**#@+
    * Constants defined for keys of the data array.
     * Identical to the name of the getter in snake case
    */
    const ENTITY_ID = 'entity_id';
    const CUSTOMER_ID = 'customer_id';
    const CREATED_AT = 'created_at';
    const COINS_RECEIVED = 'coins_received';
    const OCCASION = 'occasion';
    /**#@-*/

    /**
     * Get entity_id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set entity_id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get customer id
     *
     * @return int|null
     */
    public function getCustomerId();

    /**
     * Set customer id
     *
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId);

    /**
     * Get amount received coins
     *
     * @return float|null
     */
    public function getCoinsReceived();

    /**
     * Set amount received coins
     *
     * @param float $coinsReceived
     * @return $this
     */
    public function setCoinsReceived($coinsReceived);

    /**
     * Get occasion received coins
     *
     * @return int|null
     */
    public function getOccasion();

    /**
     * Set occasion received coins
     *
     * @param int $occasion
     * @return $this
     */
    public function setOccasion($occasion);

    /**
     * Get created at time
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created at time
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);
}
