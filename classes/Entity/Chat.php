<?php

namespace TelegramShopBot\Entity;

use TelegramShopBot\Database\Manager\OrderManager;

class Chat
{
    const STATUS_GET_CITY = 0;
    const STATUS_CITY_INSTALLED = 1;
    const STATUS_PRODUCT_SELECTED = 2;
    const STATUS_DISTRICT_SELECTED = 3;
    const STATUS_PAYMENT_SELECTED = 4;
    const STATUS_PAYD_SUCCESS = 5;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $username;

    /**
     * @var int
     */
    private $status;

    /**
     * @var City|null
     */
    private $city = null;

    /**
     * @var Order|null
     */
    private $order = null;

    /**
     * Chat constructor.
     *
     * @param int $id
     * @param string $firstName
     * @param string $username
     * @param int $status
     */
    public function __construct($id, $firstName, $username, $status = self::STATUS_GET_CITY)
    {
        $this->id         = intval($id);
        $this->firstName  = $firstName;
        $this->username   = $username;
        $this->status     = intval($status);
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = intval($id);
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): Chat
    {
        $this->city = $city;
        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): Chat
    {
        $this->status = $status;
        return $this;
    }

    public function getOrder(): ?Order
    {
        if (is_null($this->order)) {
            $this->order = OrderManager::getOneBy(['chat_id' => $this->id]);
        }

        return $this->order;
    }

    /**
     * @param Order $order
     *
     * @return $this
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;
        return $this;
    }
}