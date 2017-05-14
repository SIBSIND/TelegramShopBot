<?php

namespace TelegramShopBot\Entity;

class Chat
{
    const STATUS_GET_CITY = 0;
    const STATUS_GET_DISTRICT = 1;
    const STATUS_CITY_AND_DISTRICT_INSTALLED = 10;

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
     * @var int
     */
    private $cityId;

    /**
     * @var int
     */
    private $districtId;

    /**
     * Chat constructor.
     *
     * @param int $id
     * @param string $firstName
     * @param string $username
     * @param int $status
     * @param int $cityId
     * @param int $districtId
     */
    public function __construct(int $id, string $firstName, string $username, int $status = self::STATUS_GET_CITY, int $cityId = 0, int $districtId = 0)
    {
        $this->id         = $id;
        $this->firstName  = $firstName;
        $this->username   = $username;
        $this->status     = $status;
        $this->cityId     = $cityId;
        $this->districtId = $districtId;
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
    public function setId(int $id)
    {
        $this->id = $id;
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

    /**
     * @return int
     */
    public function getCityId()
    {
        if (empty($this->cityId))
            return null;
        return $this->cityId;
    }

    /**
     * @param int $cityId
     *
     * @return $this
     */
    public function setCityId(int $cityId)
    {
        $this->cityId = $cityId;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return $this
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getDistrictId(): int
    {
        return $this->districtId;
    }

    /**
     * @param int $districtId
     *
     * @return $this
     */
    public function setDistrictId(int $districtId)
    {
        $this->districtId = $districtId;
        return $this;
    }
}