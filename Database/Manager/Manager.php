<?php

namespace TelegramShopBot\Database\Manager;

use TelegramShopBot\Database\Database;

abstract class Manager extends Database
{
    protected static $sql;

    public static function getOneById($id)
    {
        return self::getOneBy(['id' => $id]);
    }

    public static function getOneBy(array $parameters)
    {
        $where = 'WHERE ';
        foreach ($parameters as $parameter => $value) {
            $where .= '`db_order`.`' . $parameter . '` = ' . self::$db->escapeString($value);
        }

        $data = self::$db->getRow(self::$sql . $where);

        if (empty($data)) {
            return null;
        }

        return self::createFromDb($parameters);
    }

    public static function getAll()
    {
        return self::getAllBy([]);
    }

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
            $result[] = self::createFromDb($item);
        }

        return $result;
    }

    abstract public static function create();

    abstract public static function delete($id);

    abstract public static function update($object);

    abstract protected static function createFromDb(array $data);
}