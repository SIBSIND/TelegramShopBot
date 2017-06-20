<?php

namespace TelegramShopBot;

use TelegramShopBot\Database\Manager\ChatManager;
use TelegramShopBot\Database\Manager\CityManager;
use TelegramShopBot\Database\Manager\DistrictManager;
use TelegramShopBot\Database\Manager\OrderManager;
use TelegramShopBot\Database\Manager\ProductManager;
use TelegramShopBot\Entity\Chat;
use TelegramShopBot\Entity\Order;
use TelegramShopBot\Entity\Update;

class BotCommand
{
    // TODO: доделать работу с btc
    private static $btc = false;


    private static $commands = [
        '/start' => [
            'args'     => 0,
            'function' => 'command_start'
        ],
        '/listCity' => [
            'args'     => 0,
            'function' => 'command_listCity'
        ],
        '/city' => [
            'args'     => 1,
            'function' => 'command_city'
        ],
        '/listProducts' => [
            'args'     => 0,
            'function' => 'command_listProducts'
        ],
        '/product' => [
            'args'     => 1,
            'function' => 'command_product'
        ],
        '/buy' => [
            'args'     => 1,
            'function' => 'commandBuy'
        ],
        '/help' => [
            'args'     => 0,
            'function' => 'commandHelp'
        ],
        '/cancel' => [
            'args' => 0,
            'function' => 'command_cancel'
        ],
        '/payment' => [
            'args' => 1,
            'function' => 'commandPayment'
        ],
        '/checkOrder' => [
            'args' => 0,
            'function' => 'commandCheckOrder'
        ],
    ];

    /**
     * @param Update $update
     *
     * @return bool
     */
    public static function handler(Update $update)
    {
        $inputCommand = strtolower($update->getText());
        foreach (self::$commands as $commandName => $function) {
            if (strpos($inputCommand, strtolower($commandName)) === 0) {

                $result = false;
                $funcArgs = explode('_', substr($inputCommand, strlen($commandName)));

                if (count($funcArgs) <= $function['args'] || empty($funcArgs[0])) {
                    if (empty($funcArgs[0])) {
                        $result = call_user_func('self::' . $function['function'], $update);
                    } else {
                        $result = call_user_func('self::' . $function['function'], $update, ...$funcArgs);
                    }
                }

                return $result;
            }
        }

        return false;
    }


    private static function command_start(Update $update)
    {

        Telegram::sendMessage($update->getChat(),
            '<b>' . $update->getChat()->getFirstName() . '</b> , приветствуем в 🏪 <b>' . Config::getBotName() . '</b>' .  '🏪'. PHP_EOL .
            'Перед началом работы необходимо указать город, в котором хотите совершить покупку.' . PHP_EOL
            );

        if ($update->getChat()->getStatus() !== Chat::STATUS_GET_CITY) {
            ChatManager::update($update->getChat()->setStatus(Chat::STATUS_GET_CITY));
        }

        if (!empty($update->getChat()->getOrder())) {
            OrderManager::delete($update->getChat()->getOrder()->getId());
        }

        return self::command_listCity($update);
    }


    private static function command_listCity(Update $update)
    {
        if ($update->getChat()->getStatus() !== Chat::STATUS_GET_CITY) {
            return false;
        }

        $allCity = CityManager::getAll();
        $message =
            '🏣 Доступные города:' . PHP_EOL .
            '➖➖➖➖➖➖➖➖➖➖➖➖➖➖' . PHP_EOL;

        foreach ($allCity as $city) {
            $message .=
                '🔸 <b>' . $city . '</b>' . PHP_EOL .
                '[Для выбора нажмите 👉 /city' . $city->getId() . ']' . PHP_EOL .
                '➖➖➖➖➖➖➖➖➖➖➖➖➖➖' . PHP_EOL;
        }

        Telegram::sendMessage($update->getChat(), $message);

        return true;
    }


    private static function command_city(Update $update, $cityId = null)
    {
        if ($update->getChat()->getStatus() !== Chat::STATUS_GET_CITY) {
            return false;
        }

        $city = CityManager::getById($cityId);

        if (!empty($city)) {
            $update->getChat()->setCity($city);
            $update->getChat()->setStatus(Chat::STATUS_CITY_INSTALLED);
            ChatManager::update($update->getChat());
            self::command_listProducts($update);
        } else {
            Telegram::sendMessage(
                $update->getChat(),
                'Ошибка при выборе города'
            );
        }

        return true;
    }


    private static function command_listProducts(Update $update)
    {
        if (!($update->getChat()->getStatus() === Chat::STATUS_CITY_INSTALLED ||
            $update->getChat()->getStatus() === Chat::STATUS_PRODUCT_SELECTED)) {
            Telegram::sendMessage(
                $update->getChat(),
                'Необходимо указать район города, в котором вы хотели бы получить товар.' . PHP_EOL .
                '(Чтобы просмотреть список доступных районов нажмите 👉 /listDistrict'
            );

            return false;
        }

        $products = ProductManager::getAll();
        $message =
            '🏣 <b>' . $update->getChat()->getCity() . '</b>' . PHP_EOL .
            PHP_EOL .
            'Товары в вашем городе: ' . PHP_EOL .
            '➖➖➖➖➖➖➖➖➖➖➖➖➖➖' . PHP_EOL;

        foreach ($products as $product) {
            $message .=
                '💊 <b>' . $product->getName() . '</b> ' . PHP_EOL .
                '💰 Цена: ' . $product->getPrice() . PHP_EOL .
                '[Для выбора нажмите 👉 /product' . $product->getId() . ']' . PHP_EOL .
                '➖➖➖➖➖➖➖➖➖➖➖➖➖➖' . PHP_EOL;
        }

        $message .=
            PHP_EOL .
            '❌ /cancel - чтобы сменить город';

        Telegram::sendMessage($update->getChat(), $message);

        return true;
    }


    private static function command_product(Update $update, $productId = null)
    {
        if (!($update->getChat()->getStatus() == Chat::STATUS_CITY_INSTALLED ||
            $update->getChat()->getStatus() === Chat::STATUS_PRODUCT_SELECTED)) {
            return false;
        }

        if ($update->getChat()->getStatus() === Chat::STATUS_CITY_INSTALLED) {
            if ($productId != null) {
                $product = ProductManager::getOne($productId);
                if (!empty($product)) {
                    ChatManager::update($update->getChat()->setStatus(Chat::STATUS_PRODUCT_SELECTED));
                    OrderManager::create($update->getChat(), $product);
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        $product = $update->getChat()->getOrder()->getProduct();

        $districts = DistrictManager::getAllByCityId($update->getChat()->getCity()->getId());
        $message =
            '🏣 <b>' . $update->getChat()->getCity() . '</b>' . PHP_EOL .
            PHP_EOL .
            '💊 <b>' . $product->getName() . '</b> 💊' . PHP_EOL .
            '💰 Цена: <b>' . $product->getPrice() . '</b> 💰' . PHP_EOL .
            PHP_EOL .
            '🏃 Выберите район:' . PHP_EOL .
            '➖➖➖➖➖➖➖➖➖➖➖➖➖➖' . PHP_EOL;

        foreach ($districts as $district) {
            $message .= '🔹 Район: <b>' . $district . '</b>' . PHP_EOL .
                '[Для выбора нажмите 👉 /buy' . $district->getId() . ']' . PHP_EOL .
                '➖➖➖➖➖➖➖➖➖➖➖➖➖➖' . PHP_EOL;
        }

        $message .= PHP_EOL;
        $message .= '❌ /cancel - для отмены выбора';

        Telegram::sendMessage($update->getChat(), $message);

        return true;
    }


    private static function commandBuy(Update $update, $districtId = null)
    {
        if (!($update->getChat()->getStatus() === Chat::STATUS_PRODUCT_SELECTED ||
            $update->getChat()->getStatus() === Chat::STATUS_DISTRICT_SELECTED)) {
            return false;
        }

        $product  = $update->getChat()->getOrder()->getProduct();
        $district = is_null($districtId) ? $update->getChat()->getOrder()->getDistrict() : DistrictManager::getById($districtId);

        if (empty($product) || empty($district) || $district->getCity()->getId() != $update->getChat()->getCity()->getId()) {
            return false;
        }

        $add = (self::$btc === false) ? ('') : (
            '<b>Bitcoin</b>' . PHP_EOL .
            '[Для выбора нажмите 👉 /paymentBtc]' . PHP_EOL .
            '➖➖➖➖➖➖➖➖➖➖➖➖➖➖' . PHP_EOL );

        Telegram::sendMessage($update->getChat(),
            'Вы хотите приобрести ' . PHP_EOL .
            '💊 <b>' . $product->getName() .'</b> 💊' . PHP_EOL .
            '💰 Стоимость: <b>' . $product->getPrice() . '</b> 💰' . PHP_EOL .
            '🏣 Город: <b>' . $update->getChat()->getCity() . '</b>' . PHP_EOL .
            '🏃 Район: <b>' . $district . '</b>' . PHP_EOL .
            PHP_EOL .
            '💸 Выберите способ оплаты:' . PHP_EOL .
            '➖➖➖➖➖➖➖➖➖➖➖➖➖➖' . PHP_EOL .
            '<b>Qiwi</b>' . PHP_EOL .
            '[Для выбора нажмите 👉 /paymentQiwi]' . PHP_EOL .
            '➖➖➖➖➖➖➖➖➖➖➖➖➖➖' . PHP_EOL .
            $add.
            PHP_EOL.
            '❌ /cancel - чтобы сменить район'
        );

        $update->getChat()->setStatus(Chat::STATUS_DISTRICT_SELECTED);
        ChatManager::update($update->getChat());

        $update->getChat()->getOrder()->setDistrict($district);
        OrderManager::update($update->getChat()->getOrder());

        return true;
    }


    private static function commandHelp(Update $update)
    {
        $strings = explode(PHP_EOL, file_get_contents('commandList.txt'));

        $message = 'Основные команды бота:' . PHP_EOL;
        foreach ($strings as $string) {
            $message .= '/' . $string . PHP_EOL;
        }
        Telegram::sendMessage($update->getChat(), $message);
    }


    private static function command_cancel(Update $update)
    {
        switch ($update->getChat()->getStatus()) {
            case Chat::STATUS_CITY_INSTALLED:
                ChatManager::update($update->getChat()->setStatus(Chat::STATUS_GET_CITY));
                self::command_listCity($update);
                break;

            case Chat::STATUS_PRODUCT_SELECTED:
                ChatManager::update($update->getChat()->setStatus(Chat::STATUS_CITY_INSTALLED));
                OrderManager::delete($update->getChat()->getOrder()->getId());
                self::command_listProducts($update);
                break;

            case Chat::STATUS_DISTRICT_SELECTED:
                ChatManager::update($update->getChat()->setStatus(Chat::STATUS_PRODUCT_SELECTED));
                OrderManager::update($update->getChat()->getOrder()->setDistrict(null));
                self::command_product($update);
                break;

            case Chat::STATUS_PAYMENT_SELECTED:
                ChatManager::update($update->getChat()->setStatus(Chat::STATUS_DISTRICT_SELECTED));
                OrderManager::update($update->getChat()->getOrder()
                    ->setPaymentMethod(null)
                    ->setComment(null)
                    ->setPrice(null)
                );
                self::command_buy($update);
                break;

            case Chat::STATUS_PAYD_SUCCESS:
                return false;

            default:
                self::command_start($update);
                break;
        }

        return true;
    }


    private static function commandPayment(Update $update, $method = null)
    {
        if (!($update->getChat()->getStatus() === Chat::STATUS_DISTRICT_SELECTED ||
            $update->getChat()->getStatus() === Chat::STATUS_PAYMENT_SELECTED)) {
            return false;
        }

        $method = strtolower((is_null($method)) ? $update->getChat()->getOrder()->getPaymentMethod() : $method);
        if (!(
            is_null($method) ||
            $method === 'qiwi' ||
            ($method === 'btc' && self::$btc !== false)
        )) {
            return false;
        }

        $order = $update->getChat()->getOrder();
        $comment =
            is_null($order->getComment()) ?
            substr(md5($update->getChat()->getId() . time()), 0, 10) :
            $order->getComment();

        $pay = [
            'qiwi' => [
                'name'     => 'Qiwi',
                'price'    => $order->getProduct()->getPrice(),
                'currency' => 'РУБ',
                'account'  => Config::getQiwiNumber()
            ],
            'btc' => [
                'name'  => 'Bitcoin',
                'price' => $order->getProduct()->getPrice() / 150000, // Курс BTC 150000р
                'currency' => 'BTC',
                'account'  => Config::getBtcNumber()
            ]
        ];
        $pay = $pay[$method];

        $message =
            'Вы хотите приобрести ' . PHP_EOL .
            '💊 <b>' . $order->getProduct()->getName() .'</b> 💊' . PHP_EOL .
            '💰 Стоимость: <b>' . $order->getProduct()->getPrice() . '</b> 💰' . PHP_EOL .
            '🏣 Город: <b>' . $update->getChat()->getCity() . '</b>' . PHP_EOL .
            '🏃 Район: <b>' . $order->getDistrict() . '</b>' . PHP_EOL .
            '➖➖➖➖➖➖➖➖➖➖➖➖➖➖' . PHP_EOL .
            PHP_EOL .
            'Для приобретения выбранного товара, оплатите <b>' . $pay['price'] . ' ' . $pay['currency'] .'</b> на ' . $pay['name'] . ':' . PHP_EOL .
            '<b>' . $pay['account'] . '</b>' . PHP_EOL .
            'Комментарий к платежу: <b>' . $comment . '</b>' . PHP_EOL .
            '➖➖➖➖➖➖➖➖➖➖➖➖➖➖' . PHP_EOL .
            PHP_EOL .
            'После оплаты заказа нажмите 👉 /checkOrder' . PHP_EOL .
            PHP_EOL .
            '‼ Если вы оплатите заказ, а после нажмете отмену, то восстановить оплату можно будет только чере оператора' . PHP_EOL .
            '❌ /cancel - для отмены заказа ‼'
        ;

        Telegram::sendMessage($update->getChat(), $message);

        OrderManager::update($order
            ->setPaymentMethod($method)
            ->setPrice($pay['price'])
            ->setComment($comment)
        );
        ChatManager::update($update->getChat()->setStatus(Chat::STATUS_PAYMENT_SELECTED));

        return true;
    }


    private static function commandCheckOrder(Update $update) {
        if ($update->getChat()->getStatus() !== Chat::STATUS_PAYMENT_SELECTED) {
            return false;
        }

        $qiwiPay = new QiwiCheckPayment();

        if ($qiwiPay->auth(Config::getQiwiNumber(), Config::getQiwiPassword()) == 0) {
            Telegram::sendMessage($update->getChat(), 'При проверке оплаты возникла ошибка. Попробуйте позже');
            return true;
        }

        $history = $qiwiPay->history(3);
        $order = $update->getChat()->getOrder();

        foreach ($history as $item) {
            if ($item['type'] === 'income' &&
                $item['cash'] === $order->getPrice() &&
                $item['comment'] === $order->getComment()
            ) {
                Telegram::sendMessage($update->getChat(),
                    'Ваша оплата принята.' . PHP_EOL.
                    'Курьер с вами свяжется в ближайшее время. Ожидайте.'
                );
                $update->getChat()->setStatus(Chat::STATUS_PAYD_SUCCESS);
                ChatManager::update($update->getChat());

                return true;
            }
        }

        Telegram::sendMessage($update->getChat(),
            'На данный момент оплата от вас не поступила.' . PHP_EOL .
            'Если возникли какие-то проблемы свяжитесь с оператором'
        );

        return true;
    }
}