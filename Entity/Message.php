<?php

namespace TelegramShopBot\Entity;

class Message
{
    /**
     * @var int
     */
    private $messageId;

    /**
     * @var Chat
     */
    private $chat;

    /**
     * @var int
     */
    private $date;

    /**
     * @var string
     */
    private $text;

    /**
     * Message constructor.
     *
     * @param int $messageId
     * @param int $date
     * @param string $text
     * @param Chat $chat
     */
    public function __construct($messageId, $date, $text, Chat $chat)
    {
        $this->messageId = $messageId;
        $this->date = $date;
        $this->text = $text;
        $this->chat = $chat;
    }

    /**
     * @return int
     */
    public function getMessageId(): int
    {
        return $this->messageId;
    }

    /**
     * @param int $messageId
     *
     * @return $this
     */
    public function setMessageId(int $messageId)
    {
        $this->messageId = $messageId;
        return $this;
    }

    /**
     * @return Chat
     */
    public function getChat(): Chat
    {
        return $this->chat;
    }

    /**
     * @param Chat $chat
     *
     * @return $this
     */
    public function setChat(Chat $chat)
    {
        $this->chat = $chat;
        return $this;
    }

    /**
     * @return int
     */
    public function getDate(): int
    {
        return $this->date;
    }

    /**
     * @param int $date
     *
     * @return $this
     */
    public function setDate(int $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return $this
     */
    public function setText(string $text)
    {
        $this->text = $text;
        return $this;
    }
}