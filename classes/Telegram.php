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
     * Обработка пришедшего запроса на вебхук
     * @return Update
     */
    public static function getWebHookUpdate()
    {
        $inputUpdate = json_decode(file_get_contents('php://input'), true);

        $update = new Update(
            $inputUpdate['update_id'],
            $inputUpdate['message']['message_id'],
            $inputUpdate['message']['date'],
            $inputUpdate['message']['text'],
            ChatManager::getByIdOrCreate(
                $inputUpdate['message']['chat']['id'],
                $inputUpdate['message']['chat']['first_name'],
                $inputUpdate['message']['chat']['username']
            )
        );
        UpdateManager::create($update);

        return $update;
    }
    /**
     * Получает обновления, делая запрос на telegramAPI
     * @param int $offset
     * @param int $limit
     *
     * @return Update[]|false
     */
    public static function getUpdates($offset = 0, $limit = 100)
    {
        // Метод работает только если не насстроен вебхук
        if (Config::getMethodOfUpdating() !== Config::METHOD_UPDATE_GETUPDATES) {
            return false;
        }

        $updates = self::apiRequest('getUpdates', [
            'offset' => $offset,
            'limit' => $limit,
        ]);

        $result = [];
        foreach ($updates['result'] as $update) {
            if (empty($update['message'])) { // в обновлениях нет сообщений
                exit('ERROR!'); //TODO: можно сделать работу с другими типами обновлений.
            }
            if (empty($update['message']['text'])) {
                self::sendMessage($update['message']['chat']['id'], self::MESSAGE_NOT_CONTAIN_TEXT);
                continue;
            }

            $newUpdate = new Update(
                $update['update_id'],
                $update['message']['message_id'],
                $update['message']['date'],
                $update['message']['text'],
                ChatManager::getByIdOrCreate(
                    $update['message']['chat']['id'],
                    $update['message']['chat']['first_name'],
                    $update['message']['chat']['username']
                )
            );
            $result[] = $newUpdate;
            UpdateManager::create($newUpdate);

            // Устанавливаем смещение
            if ($offset - 1 < $newUpdate->getUpdateId()) {
                $offset = $newUpdate->getUpdateId() + 1;
            }
        }

        Database::setOffset($offset);
        return $result;
    }

    /**
     * @param Chat $chat
     * @param string $messageText
     * @param array $options
     */
    public static function sendMessage(Chat $chat, string $messageText, $options = [
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => null,
        'disable_notification' => null,
        'reply_to_message_id' => null,
        'reply_markup' => null
    ])
    {
        $options = array_merge([
            'chat_id' => $chat->getId(),
            'text'    => $messageText,
        ], $options);
        $data = self::apiRequest('sendMessage', $options);

        MessageManager::create(new Message(
            $data['result']['message_id'],
            $data['result']['date'],
            $data['result']['text'],
            new Chat($data['result']['chat']['id'], '', '') // Используется только message_id
        ));
    }

    public static function getMe()
    {
        return self::apiRequest('getMe');
    }

    /**
     * @param string $method
     * @param array $options
     *
     * @return array
     */
    private static function apiRequest(string $method, $options = [])
    {
        $ch = curl_init('https://api.telegram.org/bot'. Config::getToken() . '/' . $method);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($options),
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
        ]);

        $json = curl_exec($ch);
        curl_close($ch);

        return json_decode($json, true);
    }

    /**
     * Не делает нового запроса а дает данные telegram при получении обновления через вебхук
     * @param string $method
     * @param array $options
     *
     * @return bool
     */
    private static function requestWebHook(string $method, $options = [])
    {
        if (Config::getMethodOfUpdating() === Config::METHOD_UPDATE_WEBHOOK) {
            exit('Error');
        }

        $options['method'] = $method;
        header('Content-Type: application/json');
        echo json_encode($options);

        return true;
    }

    /**
     * Устанавливает вебхук
     * @return array
     */
    public static function setWebHook()
    {
        if (!empty(Config::getPathToSSLCertificate())) {
            $postFields['certificate'] = new \CURLFile(Config::getPathToSSLCertificate());
        }

        $result = [];
        foreach (Config::getBots() as $token) {
            $postFields['url'] = Config::getWebHookURL() . $token;
            $ch = curl_init('https://api.telegram.org/bot' . $token . '/setWebhook');
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $postFields
            ]);

            $result[] = json_decode(curl_exec($ch));
            curl_close($ch);
        }

        return $result;
    }

    public static function getWebHookInfo()
    {
        return self::apiRequest('getWebhookInfo');
    }

    public static function error($string)
    {
        file_put_contents('error.log', $string . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}