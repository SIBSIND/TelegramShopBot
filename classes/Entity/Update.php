<?php

namespace TelegramShopBot\Entity;

class Update extends Message
{
    /**
     * @var int
     */
    private $updateId;

    /**
     * Update constructor.
     *
     * @param int $updateId
     * @param int $messageId
     * @param int $date
     * @param string $text
     * @param Chat $chat
     */
    public function __construct($updateId, $messageId, $date, $text, Chat $chat)
    {
        parent::__construct($messageId, $date, $text, $chat);
        $this->updateId = $updateId;
    }

    /**
     * @return int
     */
    public function getUpdateId(): int
    {
        return $this->updateId;
    }

    /**
     * @param int $updateId
     *
     * @return $this
     */
    public function setUpdateId(int $updateId)
    {
        $this->updateId = $updateId;
        return $this;
    }

}