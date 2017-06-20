<?php

namespace TelegramShopBot\Database\Manager;

use TelegramShopBot\Database\Database;
use TelegramShopBot\Entity\Chat;
use TelegramShopBot\Entity\City;
use TelegramShopBot\Entity\Product;

class ChatManager extends Database
{
    /**
     * @param int $id
     * @param string $firstName
     * @param string $username
     *
     * @return Chat
     */
    public static function getByIdOrCreate($id, $firstName, $username)
    {
        $chat = self::getById($id);

        if (empty($chat)) {
            $chat = new Chat($id, $firstName, $username);
            self::create($chat);
        }

        return $chat;
    }

    /**
     * @param int $id
     *
     * @return null|Chat
     */
    public static function getById($id)
    {
        $data = self::$db->getRow(
            'SELECT A.*, B.name AS `city_name` 
              FROM db_chat AS A 
              LEFT JOIN db_city AS B ON A.city_id = B.id  
              WHERE A.id = ?i',
            intval($id)
        );

        if (empty($data)) {
            return null;
        }
        $chat = new Chat(
            $data['id'],
            $data['first_name'],
            $data['username'],
            $data['status']
        );

        if (!empty($data['city_id'])) {
            $chat->setCity(new City($data['city_id'], $data['city_name']));
        }

        return $chat;
    }

    /**
     * @param Chat $chat
     */
    public static function create(Chat $chat)
    {
        self::$db->query('INSERT INTO db_chat SET ?u', [
            'id' => $chat->getId(),
            'first_name' => $chat->getFirstName(),
            'username' => $chat->getUsername(),
            'status' => $chat->getStatus(),
            'city_id' => ($chat->getCity() == null) ? null : $chat->getCity()->getId(),
        ]);
    }

    /**
     * @param Chat $chat
     */
    public static function update(Chat $chat)
    {
        self::$db->query('UPDATE db_chat SET ?u WHERE id = ?i', [
            'first_name' => $chat->getFirstName(),
            'username' => $chat->getUsername(),
            'status' => $chat->getStatus(),
            'city_id' => ($chat->getCity() == null) ? null : $chat->getCity()->getId(),
        ], $chat->getId());
    }
}