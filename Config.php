<?php

namespace TelegramShopBot;

class Config
{
    private static $token = '306518492:AAHyjCKCq3BmdKYpDt_F_Ety6mcjg0dgb2w';

    private static $dbHost = 'localhost';
    private static $dbDatabase = 'TelegramShopBot';
    private static $dbUser = 'root';
    private static $dbPassword = '1234';

    public const METHOD_UPDATE_GETUPDATES = 0;
    public const METHOD_UPDATE_WEBHOOK = 1;
    private static $methodOfUpdating = self::METHOD_UPDATE_WEBHOOK;
    private static $webHookURL = '';
    private static $PathToSSLCertificate = '/ssl/YOURPUBLIC.pem';

    /**
     * @return string
     */
    public static function getToken(): string
    {
        return self::$token;
    }

    /**
     * @return string
     */
    public static function getDbHost(): string
    {
        return self::$dbHost;
    }

    /**
     * @return string
     */
    public static function getDbDatabase(): string
    {
        return self::$dbDatabase;
    }

    /**
     * @return string
     */
    public static function getDbUser(): string
    {
        return self::$dbUser;
    }

    /**
     * @return string
     */
    public static function getDbPassword(): string
    {
        return self::$dbPassword;
    }

    /**
     * @return int
     */
    public static function getMethodOfUpdating(): int
    {
        return self::$methodOfUpdating;
    }

    /**
     * @return string
     */
    public static function getWebHookURL(): string
    {
        return self::$webHookURL;
    }

    /**
     * @return string
     */
    public static function getPathToSSLCertificate(): string
    {
        return self::$PathToSSLCertificate;
    }


    private function __construct()
    {
    }
}

