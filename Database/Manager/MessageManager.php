<?php

namespace TelegramShopBot\Database\Manager;

use TelegramShopBot\Database\Database;
use TelegramShopBot\Entity\Message;

class MessageManager extends Database
{
    /**
     * @param Message $message
     */
    public static function create(Message $message)
    {
        self::$db->query('INSERT INTO db_message SET ?u',[
            'chat_id'    => $message->getChat()->getId(),
            'message_id' => $message->getMessageId(),
            'date'       => $message->getDate(),
            'text'       => $message->getText(),
        ]);
    }

}