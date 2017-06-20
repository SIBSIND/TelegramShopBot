<?php

namespace TelegramShopBot;

use TelegramShopBot\Database\Database;

class Config extends Database
{
    private static $bots = null;

    private static $dbHost;
    private static $dbDatabase;
    private static $dbUser;
    private static $dbPassword;

    const METHOD_UPDATE_GETUPDATES = 0;
    const METHOD_UPDATE_WEBHOOK = 1;
    private static $methodOfUpdating;
    private static $webHookURL;
    private static $pathToSSLCertificate;

    private static $qiwiNumber;
    private static $qiwiPassword;

    private static $btcNumber;

    /**
     * @param array $settings
     */
    public static function init($settings) {
        if (!empty($settings['bots'])) {
            self::$bots =  $settings['bots'];
        }

        self::$dbHost = $settings['dbHost'];
        self::$dbDatabase = $settings['dbDatabase'];
        self::$dbUser = $settings['dbUser'];
        self::$dbPassword = $settings['dbPassword'];

        self::$methodOfUpdating = intval($settings['methodOfUpdating']);
        self::$webHookURL = $settings['webHookURL'] . 'index.php?token=';
        self::$pathToSSLCertificate = $settings['pathToSSLCertificate'];

        self::$qiwiNumber = $settings['qiwiNumber'];
        self::$qiwiPassword = $settings['qiwiPassword'];

        self::$btcNumber = $settings['btcNumber'];
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
        return self::$pathToSSLCertificate;
    }

    /**
     * @return string
     */
    public static function getQiwiNumber(): string
    {
        return self::$qiwiNumber;
    }

    /**
     * @return string
     */
    public static function getQiwiPassword(): string
    {
        return self::$qiwiPassword;
    }

    /**
     * @return string
     */
    public static function getBtcNumber(): string
    {
        return self::$btcNumber;
    }

    /**
     * @return array|null
     */
    public static function getBots(): ?array
    {
        return self::$bots;
    }

    private static $token = null;
    private static $botName = null;

    /**
     * @return null|string
     */
    public static function getToken(): ?string
    {
        return self::$token;
    }

    /**
     * @param string $token
     */
    public static function setToken(string $token)
    {
        self::$token = $token;
    }

    /**
     * @return null|string
     */
    public static function getBotName(): ?string
    {
        return self::$botName;
    }

    /**
     * @param string $botName
     */
    public static function setBotName(string $botName)
    {
        self::$botName = $botName;
    }


    private function __construct()
    {
    }
}

