<?php

namespace TelegramShopBot\Database\Manager;

use TelegramShopBot\Database\Database;
use TelegramShopBot\Entity\Chat;

class ChatManager extends Database
{
    /**
     * @param int $id
     *
     * @return null|Chat
     */
    public static function getById($id)
    {
        $data = self::$db->getRow('SELECT * FROM db_chat WHERE id = ?i', $id);

        return (empty($data)) ? null :
            new Chat(
                intval($data['id']),
                $data['first_name'],
                $data['username'],
                intval($data['status']),
                @intval($data['cityId'])
            );
    }

    /**
     * @param Chat $chat
     */
    public static function create(Chat $chat)
    {
        self::$db->query('INSERT INTO db_chat SET ?u', [
            'id'         => $chat->getId(),
            'first_name' => $chat->getFirstName(),
            'username'   => $chat->getUsername(),
            'status'     => $chat->getStatus(),
            'city_id'    => $chat->getCityId(),
        ]);
    }

    /**
     * @param Chat $chat
     */
    public static function update(Chat $chat)
    {
        self::$db->query('UPDATE db_chat SET ?u WHERE id = ?i', [
            'first_name' => $chat->getFirstName(),
            'username'   => $chat->getUsername(),
            'status'     => $chat->getStatus(),
            'city_id'    => $chat->getCityId(),
        ], $chat->getId());
    }
}