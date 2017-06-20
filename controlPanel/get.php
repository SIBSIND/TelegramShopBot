<?php
use TelegramShopBot\Config;
use TelegramShopBot\controlPanel\HandlerAPI;
use TelegramShopBot\Database\Database;
use TelegramShopBot\Database\Manager\CityManager;
use TelegramShopBot\Database\Manager\DistrictManager;
use TelegramShopBot\Database\Manager\ProductManager;

require '../autoload.php';
require '../settings.php';

Config::init($settings);
Database::initDb();

$entity = strtolower($_REQUEST['entity']);
$method = $_REQUEST['method'];
unset($_REQUEST['method']);
unset($_REQUEST['entity']);

$handler = new HandlerAPI($entity, $method);
$handler->handle($_REQUEST);