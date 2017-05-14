<?php

namespace TelegramShopBot\Database\Manager;

use TelegramShopBot\Database\Database;
use TelegramShopBot\Entity\Update;

class UpdateManager extends Database
{
    /**
     * @param $updateId
     *
     * @return false|Update
     */
    public static function getById($updateId)
    {
        $data = self::$db->getRow('SELECT * FROM db_message WHERE update_id = ?i', $updateId);

        return (empty($data)) ? false :
            new Update(
                $data['update_id'],
                $data['message_id'],
                $data['date'],
                $data['text'],
                ChatManager::getById($data['chat_id'])
            );
    }

    /**
     * @param Update $update
     */
    public static function create(Update $update)
    {
        self::$db->query('INSERT INTO db_message SET ?u',[
            'chat_id'    => $update->getChat()->getId(),
            'update_id'  => $update->getUpdateId(),
            'message_id' => $update->getMessageId(),
            'date'       => $update->getDate(),
            'text'       => $update->getText(),
        ]);
    }

}