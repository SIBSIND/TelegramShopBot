<?php

namespace TelegramShopBot\Database\Manager;

use TelegramShopBot\Database\Database;
use TelegramShopBot\Entity\City;

class CityManager extends Database
{
    /**
     * @param $id
     *
     * @return null|City
     */
    public static function getById($id)
    {
        $data = self::$db->getRow('SELECT * FROM db_city WHERE id = ?i', intval($id));

        return (empty($data)) ? null :
            new City($data['id'], $data['name']);
    }

    /**
     * @param $name
     *
     * @return null|City
     */
    public static function getByName($name)
    {
        $data = self::$db->getRow('SELECT * FROM db_city WHERE `name` = ?s', mb_strtolower($name));

        return (empty($data)) ? null :
            new City($data['id'], $data['name']);
    }

    /**
     * @param string $name
     *
     * @return City
     */
    public static function create(string $name): City
    {
        self::$db->query('INSERT INTO db_city SET `name` = ?s', mb_strtolower($name));

        return new City(self::$db->insertId(), $name);
    }

    /**
     * @return City[]
     */
    public static function getAll()
    {
        $data = self::$db->getAll('SELECT * FROM db_city ORDER BY `name`');
        $result = [];
        foreach ($data as $item) {
            $result[] = new City($item['id'], $item['name']);
        }

        return $result;
    }


    public static function update(City $city)
    {
        self::$db->query('UPDATE db_city SET `name` = ?s WHERE id = ?i', $city->getName(), $city->getId());
    }

    public static function delete($id)
    {
        self::$db->query('DELETE FROM db_city WHERE id = ?i', intval($id));
    }

}