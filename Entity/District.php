<?php

namespace TelegramShopBot\Entity;

class District {
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $cityId;

    /**
     * @var string
     */
    private $name;

    /**
     * District constructor.
     *
     * @param int $id
     * @param int $cityId
     * @param string $name
     */
    public function __construct(int $id, int $cityId, string $name)
    {
        $this->id = $id;
        $this->cityId = $cityId;
        $this->name = $name;
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
     * @return int
     */
    public function getCityId(): int
    {
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
     * @return string
     */
    public function getName(): string
    {
        return ucwords($this->name);
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}