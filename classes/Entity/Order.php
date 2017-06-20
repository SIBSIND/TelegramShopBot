<?php

namespace TelegramShopBot\Entity;

class Order
{
    const STATUS_NEW_ORDER = 0;
    const STATUS_WAIT_PAYMENT = 1;
    const STATUS_PAID = 2;

    /**
     * @var int
     */
    private $id;

    /**
     * @var Chat
     */
    private $chat;

    /**
     * @var Product
     */
    private $product;

    /**
     * @var District|null
     */
    private $district;

    /**
     * @var int
     */
    private $status;

    /**
     * @var string|null
     */
    private $paymentMethod;

    /**
     * @var string|null
     */
    private $comment;

    /**
     * @var float|null
     */
    private $price;

    /**
     * Order constructor.
     *
     * @param int $id
     * @param int $status
     * @param string|null $paymentMethod
     * @param string|null $comment
     * @param float|null $price
     * @param Chat $chat
     * @param Product $product
     * @param District|null $district
     */
    public function __construct($id, $status, $paymentMethod, $comment, $price, Chat $chat, Product $product, $district = null)
    {
        $this->id = intval($id);
        $this->status = intval($status);
        $this->paymentMethod = $paymentMethod;
        $this->comment = $comment;
        $this->price = (is_null($price)) ? null : floatval($price);
        $this->chat = $chat;
        $this->product = $product;
        $this->district = $district;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Chat
     */
    public function getChat(): Chat
    {
        return $this->chat;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
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
    public function setStatus($status)
    {
        $this->status = intval($status);
        return $this;
    }

    /**
     * @return District|null
     */
    public function getDistrict(): ?District
    {
        return $this->district;
    }

    /**
     * @param District|null $district
     *
     * @return $this
     */
    public function setDistrict(?District $district)
    {
        $this->district = $district;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    /**
     * @param string|null $paymentMethod
     *
     * @return $this
     */
    public function setPaymentMethod(?string $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param null|string $comment
     *
     * @return $this
     */
    public function setComment(?string $comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     *
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = (is_null($price)) ? $price : floatval($price);
        return $this;
    }





}