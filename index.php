<?php

use TelegramShopBot\Config;
use TelegramShopBot\Database\Database;;
use TelegramShopBot\Database\Manager\ChatManager;
use TelegramShopBot\Database\Manager\CityManager;
use TelegramShopBot\Database\Manager\DistrictManager;
use TelegramShopBot\Entity\Chat;
use TelegramShopBot\Telegram;

require 'autoload.php';

Database::initDb();

if (Config::getMethodOfUpdating() === Config::METHOD_UPDATE_WEBHOOK) {
    if (isset($_GET['installHandler'])) {
        if ($_GET['installHandler'] === Config::getToken()) {
            Telegram::setWebHook();
        }
    }
} elseif (Config::getMethodOfUpdating() === Config::METHOD_UPDATE_GETUPDATES) {

    $updates = Telegram::getUpdates(Database::getOffset());

    foreach ($updates as $update) {

        // Является ли сообщение коммандой
        if (!Telegram::botCommand($update)) {

            // Статус чата не совпадает со статусом в БД (был изменен)
            if (ChatManager::getById($update->getChat()->getId())->getStatus() === $update->getChat()->getStatus()) {

                // По статусу мы ждем от пользователя, чтобы он ввел город
                if ($update->getChat()->getStatus() === Chat::STATUS_GET_CITY) {
                    $city = CityManager::getByName($update->getText());

                    if (empty($city)) {
                        Telegram::sendMessage(
                            $update->getChat(),
                            'Перед началом работы необходимо указать город, в котором вы хотите совершить покупку.' . PHP_EOL .
                            '(Для списка доступных городов введите /listCity)'
                        );
                    } else {
                        $update->getChat()->setCityId($city->getId());
                        $update->getChat()->setStatus(Chat::STATUS_GET_DISTRICT);
                        ChatManager::update($update->getChat());
                        Telegram::sendMessage(
                            $update->getChat(),
                            'Город "' . $city->getName() . '" установлен успешно. ' . PHP_EOL .
                            'Теперь введите район, в котором вы хотите получить товар'
                        );
                    }

                } // Ожидаем ввод района города
                elseif ($update->getChat()->getStatus() === Chat::STATUS_GET_DISTRICT) {
                    $district = DistrictManager::getByName($update->getText());

                    if (empty($district)) {
                        Telegram::sendMessage(
                            $update->getChat(),
                            'Необходимо указать район города, в котором вы хотели бы получить товар.' . PHP_EOL .
                            '(Чтобы просмотреть список доступных районов введите /listDistrict'
                        );
                    } else {
                        $update->getChat()->setDistrictId($district->getId());
                        $update->getChat()->setStatus(Chat::STATUS_CITY_AND_DISTRICT_INSTALLED);
                        ChatManager::update($update->getChat());
                        Telegram::sendMessage(
                            $update->getChat(),
                            '"' . $district->getName() . '" район установлен.'
                        );
                    }
                } elseif ($update->getChat()->getStatus() === Chat::STATUS_CITY_AND_DISTRICT_INSTALLED) {
                    Telegram::sendMessage($update->getChat(), 'status3');
                }
            }
        }
    }
}