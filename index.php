<?php

use TelegramShopBot\BotLogic;
use TelegramShopBot\Config;
use TelegramShopBot\Database\Database;
use TelegramShopBot\Database\Manager\OrderManager;
use TelegramShopBot\Telegram;

require 'autoload.php';
if (file_exists('settings_dev.php')) {
    require 'settings_dev.php';
} else {
    require 'settings.php';
}

Config::init($settings);
Database::initDb();

if (!empty($_GET['webHook'])) {
    if (Config::getMethodOfUpdating() !== Config::METHOD_UPDATE_WEBHOOK) {
        exit('Сначала вы должны в настройках указать верно метод получения обновлений');
    }

    if ($_GET['webHook'] === 'set') {
        print_r(Telegram::setWebHook());
    } elseif ($_GET['webHook'] === 'info') {
        print_r(Telegram::getWebHookInfo());
    } else {
        exit(
            'Нужно указать действие, которое необходимо выполнить: <br>' . PHP_EOL .
            'set - установить вебхук <br>' . PHP_EOL .
            'info - получить информацию по вебхуку <br>' . PHP_EOL
        );
    }
    exit('<br>' . PHP_EOL);
}

if (Config::getMethodOfUpdating() === Config::METHOD_UPDATE_WEBHOOK) {

    if (empty($_GET['token']) || !in_array($_GET['token'], Config::getBots(), true)) {
        exit('Ошибка токена');
    }

    foreach (Config::getBots() as $botName => $token) {
        if ($token === $_GET['token']) {
            Config::setBotName($botName);
            Config::setToken($token);
        }
    }

    $update = Telegram::getWebHookUpdate();
    BotLogic::handlerUpdate($update);

} elseif (Config::getMethodOfUpdating() === Config::METHOD_UPDATE_GETUPDATES) {

    $updates = Telegram::getUpdates(Database::getOffset());

    foreach ($updates as $update) {
        BotLogic::handlerUpdate($update);
    }
}