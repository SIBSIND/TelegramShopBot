<?php

namespace TelegramShopBot;

use TelegramShopBot\Database\Database;
use TelegramShopBot\Database\Manager\CityManager;
use TelegramShopBot\Database\Manager\MessageManager;
use TelegramShopBot\Database\Manager\UpdateManager;
use TelegramShopBot\Database\Manager\ChatManager;
use TelegramShopBot\Entity\Chat;
use TelegramShopBot\Entity\Message;
use TelegramShopBot\Entity\Update;

class Telegram
{
    private const MESSAGE_NOT_CONTAIN_TEXT = 'Я понимаю только текстовые сообщения';

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return Update[]
     */
    public static function getUpdates($offset = 0, $limit = 100)
    {
        $updates = self::query('getUpdates', [
            'offset' => $offset,
            'limit' => $limit,
        ]);

        $result = [];
        foreach ($updates['result'] as $update) {
            if (empty($update['message'])) {
                exit('ERROR!');
            }
            if (empty($update['message']['text'])) {
                self::sendMessage($update['message']['chat']['id'], self::MESSAGE_NOT_CONTAIN_TEXT);
                continue;
            }

            $chat = ChatManager::getById($update['message']['chat']['id']);

            // Если этого чата не было еще, то добавляем его
            if ($chat == false) {
                $chat = new Chat(
                    $update['message']['chat']['id'],
                    $update['message']['chat']['first_name'],
                    $update['message']['chat']['username']
                );
                ChatManager::create($chat);
            }

            $newUpdate = new Update(
                $update['update_id'],
                $update['message']['message_id'],
                $update['message']['date'],
                $update['message']['text'],
                $chat
            );
            $result[] = $newUpdate;
            UpdateManager::create($newUpdate);

            // Устанавливаем смещение
            if ($offset-1 < $newUpdate->getUpdateId()) {
                $offset = $newUpdate->getUpdateId() + 1;
            }
        }

        Database::setOffset($offset);
        return $result;
    }

    public static function sendMessage(Chat $chat, $text)
    {
        $data = self::query('sendMessage', [
            'chat_id' => $chat->getId(),
            'text' => $text,
        ]);

        MessageManager::create(new Message(
            $data['result']['message_id'],
            $data['result']['date'],
            $data['result']['text'],
            ChatManager::getById($data['result']['chat']['id'])
        ));
    }

    /**
     * @param Update $update
     *
     * @return bool (true if update text is botCommand)
     */
    public static function botCommand(Update $update)
    {
        $command = $update->getText();
        if ($command === '/listCity') {
            $city = CityManager::getAll();
            $message = 'Доступные города:' . PHP_EOL;
            foreach ($city as $item) {
                $message .= $item . PHP_EOL;
            }

            self::sendMessage($update->getChat(), $message);
        } else {
            return false;
        }

        return true;
    }

    public static function getMe()
    {
        return self::query('getMe');
    }

    /**
     * @param string $method
     * @param array $options
     *
     * @return array
     */
    private static function query(string $method, $options = [])
    {
        $optionsString = (empty($options)) ? '' : '?' . http_build_query($options);
        $json = file_get_contents('https://api.telegram.org/bot'. Config::getToken() . '/' . $method . $optionsString);

        return json_decode($json, true);
    }

    public static function setWebHook()
    {
        $url = 'https://api.telegram.org/bot' . Config::getToken() . '/setWebhook';
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_SAFE_UPLOAD => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => [
                'url'         => Config::getWebHookURL(),
                'certificate' => '@' . realpath(Config::getPathToSSLCertificate())
            ]
        ]);

        $result = curl_exec($ch);
        echo "<pre>";
        print_r($result);
        echo "</pre>";
        curl_close($ch);

    }

}