<?php

namespace TelegramShopBot\Database\Manager;

use TelegramShopBot\Database\Database;
use TelegramShopBot\Entity\District;

class DistrictManager extends Database
{
    /**
     * @param int $id
     *
     * @return null|District
     */
    public static function getById(int $id)
    {
        $data = self::$db->getRow('SELECT * FROM db_district WHERE id = ?i', $id);

        return (empty($data)) ? null :
            new District(intval($data['id']), intval($data['city_id']), $data['name']);
    }

    /**
     * @param string $name
     *
     * @return null|District
     */
    public static function getByName(string $name)
    {
        $data = self::$db->getRow('SELECT * FROM db_district WHERE `name` = ?s', mb_strtolower($name));

        return (empty($data)) ? null :
            new District(intval($data['id']), intval($data['city_id']), $data['name']);
    }

    /**
     * @param int $cityId
     *
     * @return null|District
     */
    public static function getByCity(int $cityId)
    {
        $data = self::$db->getRow('SELECT * FROM db_district WHERE city_id = ?i', $cityId);

        return (empty($data)) ? null :
            new District(intval($data['id']), intval($data['city_id']), $data['name']);
    }

    /**
     * @param int $cityId
     * @param string $name
     *
     * @return int
     */
    public static function create(int $cityId, string $name)
    {
        self::$db->query('INSERT INTO db_district SET city_id = ?i, `name` = ?s', $cityId, mb_strtolower($name));

        return self::$db->insertId();
    }


}