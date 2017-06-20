<?php

namespace TelegramShopBot\Database\Manager;

use TelegramShopBot\Database\Database;
use TelegramShopBot\Entity\Chat;
use TelegramShopBot\Entity\City;
use TelegramShopBot\Entity\District;
use TelegramShopBot\Entity\Order;
use TelegramShopBot\Entity\Product;

class OrderManager extends Database
{
    private static $sql =  <<< SQL
SELECT
  db_order.payment_method,
  db_order.id           AS `order_id`,
  db_order.status       AS `order_status`,
  db_order.district_id  AS `district_id`,
  db_order.comment      AS `order_comment`,
  db_order.price        AS `order_price`,
  db_chat.id            AS `chat_id`,
  db_chat.first_name    AS `chat_first_name`,
  db_chat.username      AS `chat_username`,
  db_chat.status        AS `chat_status`,
  db_product.id         AS `product_id`,
  db_product.name       AS `product_name`,
  db_product.price      AS `product_price`,
  db_district.name      AS `district_name`,
  db_city.id            AS `city_id`,
  db_city.name          AS `city_name`
FROM db_order
  LEFT JOIN db_chat     ON db_order.chat_id     = db_chat.id
  LEFT JOIN db_product  ON db_order.product_id  = db_product.id
  LEFT JOIN db_city     ON db_chat.city_id      = db_city.id
  LEFT JOIN db_district ON db_order.district_id = db_district.id

SQL;

    public static function getOne($orderId): ?Order
    {
        return self::getOneBy(['id' => intval($orderId)]);
    }
    public static function getOneBy(array $parameters): ?Order
    {
        $where = 'WHERE ';
        foreach ($parameters as $parameter => $value) {
            $where .= '`db_order`.`' . $parameter . '` = ' . self::$db->escapeString($value);
        }

        $data = self::$db->getRow(self::$sql . $where);

        if (empty($data)) {
            return null;
        }

        $city = new City($data['city_id'], $data['city_name']);
        $district = null;
        if (!empty($data['district_id']))
            $district = new District($data['district_id'], $data['district_name'], $city);
        $chat = new Chat($data['chat_id'], $data['chat_first_name'], $data['chat_username'], $data['chat_status']);
        $chat->setCity($city);
        $product = new Product($data['product_id'], $data['product_name'], $data['product_price']);
        $order = new Order(
            $data['order_id'],
            $data['order_status'],
            $data['payment_method'],
            $data['order_comment'],
            $data['order_price'],
            $chat,
            $product,
            $district
        );
        $chat->setOrder($order);

        return $order;

    }


    /**
     * @return Order[]
     */
    public static function getAll()
    {
        return self::getAllBy([]);
    }
    /**
     * @param array $parameters
     *
     * @return null|Order[]
     */
    public static function getAllBy(array $parameters)
    {
        $where = '';
        if (!empty($parameters)) {
            $where = 'WHERE ';
            foreach ($parameters as $parameter => $value) {
                $where .= '`db_order`.`' . $parameter . '` = ' . self::$db->escapeString($value);
            }
        }

        $data = self::$db->getAll(self::$sql . $where);

        if (empty($data)) {
            return null;
        }

        $result = [];
        foreach ($data as $item) {
            $city = new City($item['city_id'], $item['city_name']);
            $district = null;
            if (!empty($item['district_id']))
                $district = new District($item['district_id'], $item['district_name'], $city);
            $chat = new Chat($item['chat_id'], $item['chat_first_name'], $item['chat_username'], $item['chat_status']);
            $chat->setCity($city);
            $product = new Product($item['product_id'], $item['product_name'], $item['product_price']);
            $order = new Order(
                $item['order_id'],
                $item['order_status'],
                $item['payment_method'],
                $data['order_comment'],
                $data['order_price'],
                $chat,
                $product,
                $district
            );
            $chat->setOrder($order);
            $result[] = $order;
        }

        return $result;
    }

    /**
     * @param Chat $chat
     * @param Product $product
     */
    public static function create(Chat $chat, Product $product)
    {
        self::$db->query(
            'INSERT INTO db_order SET ?u',
            [
                'chat_id'    => $chat->getId(),
                'product_id' => $product->getId(),
            ]
        );
    }

    /**
     * @param int $orderId
     */
    public static function delete($orderId)
    {
        self::$db->query('DELETE FROM db_order WHERE id = ?i', intval($orderId));
    }

    /**
     * @param Order $order
     */
    public static function update(Order $order)
    {
        self::$db->query(
            'UPDATE db_order SET ?u WHERE id = ?i',
            [
                'product_id'     => $order->getProduct()->getId(),
                'status'         => $order->getStatus(),
                'district_id'    => (is_null($order->getDistrict())) ? null : $order->getDistrict()->getId(),
                'payment_method' => $order->getPaymentMethod(),
                'comment'        => $order->getComment(),
                'price'          => $order->getPrice()
            ],
            intval($order->getId())
        );
    }
}