<?php

namespace TelegramShopBot\Entity;

class District {
    /**
     * @var int
     */
    private $id;

    /**
     * @var City
     */
    private $city;

    /**
     * @var string
     */
    private $name;

    /**
     * District constructor.
     *
     * @param int $id
     * @param string $name
     * @param City $city
     */
    public function __construct($id, string $name, City $city)
    {
        $this->id = intval($id);
        $this->city = $city;
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
     * @return City
     */
    public function getCity(): City
    {
        return $this->city;
    }

    /**
     * @param City $city
     *
     * @return $this
     */
    public function setCity(City $city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return mb_convert_case($this->name, MB_CASE_TITLE);
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