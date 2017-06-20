<?php

namespace TelegramShopBot\Database\Manager;

use TelegramShopBot\Database\Database;
use TelegramShopBot\Entity\Product;

class ProductManager extends Database
{
    private static $sql = 'SELECT * FROM db_product ';
    /**
     * @return Product[]
     */
    public static function getAll()
    {
        $data = self::$db->getAll(self::$sql);

        $products = [];
        foreach ($data as $item) {
            $products[] = new Product($item['id'], $item['name'], $item['price']);
        }

        return $products;
    }

    /**
     * Get one product by id
     *
     * @param $productId
     *
     * @return null|Product
     */
    public static function getOne($productId): ?Product
    {
        return self::getOneBy(['id' => intval($productId)]);
    }

    /**
     * @param array $parameters
     *
     * @return null|Product
     */
    public static function getOneBy(array $parameters): ?Product
    {
        $data = self::$db->getRow(self::$sql . 'WHERE ?u', $parameters);

        if (empty($data))
            return null;

        return new Product($data['id'], $data['name'], $data['price']);
    }

    /**
     * @param string $name
     * @param int $price
     *
     * @return Product
     */
    public static function create($name, $price)
    {
        self::$db->query('INSERT INTO db_product SET `name` = ?s, `price` = ?i', $name, intval($price));

        return new Product(self::$db->insertId(), $name, $price);
    }

    public static function update(Product $product)
    {
        self::$db->query('UPDATE db_product SET ?u WHERE id = ?i',
            [
                'name' => $product->getName(),
                'price' => $product->getPrice()
            ], $product->getId());
    }

    public static function delete($id)
    {
        self::$db->query('DELETE FROM db_product WHERE id = ?i', intval($id));
    }
}