<?php

namespace TelegramShopBot\Database\Manager;

use TelegramShopBot\Database\Database;
use TelegramShopBot\Entity\City;
use TelegramShopBot\Entity\District;
use TelegramShopBot\Telegram;

class DistrictManager extends Database
{
    /**
     * @param int $id
     *
     * @return null|District
     */
    public static function getById($id)
    {
        $data = self::$db->getRow(
            'SELECT D.*, C.name AS `city_name` 
            FROM db_district AS D
              LEFT JOIN db_city AS C ON D.city_id = C.id
            WHERE D.id = ?i',
            intval($id)
        );

        if (empty($data)) {
            return null;
        }

        $city = new City($data['city_id'], $data['city_name']);
        $district = new District($data['id'], $data['name'], $city);

        return $district;
    }

    /**
     * @param string $name
     *
     * @return null|District
     */
    public static function getByName(string $name)
    {
        $data = self::$db->getRow(
            'SELECT D.*, C.name AS `city_name` 
            FROM db_district AS D
              LEFT JOIN db_city AS C ON D.city_id = C.id
            WHERE D.`name` = ?s',
            mb_strtolower($name)
        );

        if (empty($data)) {
            return null;
        }

        $city = new City($data['city_id'], $data['city_name']);
        $district = new District($data['id'], $data['name'], $city);

        return $district;
    }

    /**
     * @param int $cityId
     *
     * @return null|District[]
     */
    public static function getAllByCityId($cityId)
    {
        $data = self::$db->getAll(
            'SELECT D.*, C.name AS `city_name` 
            FROM db_district AS D
              LEFT JOIN db_city AS C ON D.city_id = C.id 
            WHERE D.city_id = ?i', intval($cityId));

        if (empty($data)) {
            return null;
        }

        $districts = [];
        foreach ($data as $item) {
            $city = new City($item['city_id'], $item['city_name']);
            $district = new District($item['id'], $item['name'], $city);
            $districts[] = $district;
        }

        return $districts;
    }

    /**
     * @param int $cityId
     * @param string $name
     *
     * @return District
     */
    public static function create($cityId, string $name)
    {
        self::$db->query('INSERT INTO db_district SET ?u',[
            'city_id' => intval($cityId),
            'name'    => mb_strtolower($name)
            ]
        );

        return new District(self::$db->insertId(), $name, CityManager::getById($cityId));
    }

    public static function update(District $district)
    {
        self::$db->query('UPDATE db_district SET `name` = ?s WHERE id = ?i', $district->getName(), $district->getId());
    }

    public static function delete($id)
    {
        self::$db->query('DELETE FROM db_district WHERE id = ?i', intval($id));
    }
}