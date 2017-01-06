<?php

namespace StripePayment\Event;

use Thelia\Core\Event\ActionEvent;

/**
 * Class ChargeStripeCustomerEvent
 * @package StripePayment\Event
 */
class ChargeStripeCustomerEvent extends ActionEvent 
{
    protected $customerId;
    protected $amount;

    /**
     * ChargeStripeCustomerEvent constructor.
     * @param $customerId
     * @param $amount
     */
    public function __construct($customerId, $amount)
    {
        $this->customerId = $customerId;
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @param $customerId
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }
}