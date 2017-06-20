<?php

namespace TelegramShopBot\Database;

use TelegramShopBot\Config;

class Database
{
    /**
     * @var SafeMySQL
     */
    protected static $db = null;

    /**
     * Initialize database connection class
     */
    public static function initDb()
    {
        if (self::$db == null) {
            self::$db = new SafeMySQL([
                'host'    => Config::getDbHost(),
                'user'    => Config::getDbUser(),
                'pass'    => Config::getDbPassword(),
                'db'      => Config::getDbDatabase(),
                'charset' => 'utf8mb4',
            ]);
        }
    }

    /**
     * @param int $offset
     */
    public static function setOffset($offset)
    {
        self::$db->query('UPDATE db_config SET offset = ?i', intval($offset));
    }

    /**
     * @return int
     */
    public static function getOffset(): int
    {
        return intval(self::$db->getOne('SELECT `offset` FROM db_config WHERE id = 1'));
    }

    private function __construct()
    {
    }
}