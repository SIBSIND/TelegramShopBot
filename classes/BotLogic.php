<?php

namespace TelegramShopBot;

use TelegramShopBot\Database\Manager\ChatManager;
use TelegramShopBot\Entity\Chat;
use TelegramShopBot\Entity\Update;

class BotLogic
{
    public static function handlerUpdate(Update $update)
    {
        if (BotCommand::handler($update)) {
            return;
        }
        // Статус чата не совпадает со статусом в БД (был изменен)
        if (ChatManager::getById($update->getChat()->getId())->getStatus() !== $update->getChat()->getStatus()) {
            return;
        }

        $message = '';
        switch ($update->getChat()->getStatus()) {

            case Chat::STATUS_GET_CITY:
                $message =
                    'Перед началом работы необходимо указать город, в котором вы хотите совершить покупку.' . PHP_EOL .
                    '(Для списка доступных городов 👉 /listCity)';
                break;

            case Chat::STATUS_CITY_INSTALLED:

                $message = 'Список доступных товаров 👉 /listProducts';
                break;

            case Chat::STATUS_PRODUCT_SELECTED:
                $message = 'Нужно подтвердить покупку или отменить её 👉 /product';
                break;

            case Chat::STATUS_DISTRICT_SELECTED:
                $message = 'Необходимо выбрать способ оплаты 👉 /buy';
                break;

            case Chat::STATUS_PAYMENT_SELECTED:
                $message = 'Оплатите заказ, либо отмените его 👉 /payment';
                break;

            case Chat::STATUS_PAYD_SUCCESS:
                $message = 'Ожидайте, когда с вами свяжутся.';
                break;
        }

        Telegram::sendMessage($update->getChat(), $message);
    }

}