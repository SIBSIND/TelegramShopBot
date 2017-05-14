<?php

use TelegramShopBot\Database\Database;
use TelegramShopBot\Database\Manager\CityManager;
use TelegramShopBot\Database\Manager\DistrictManager;

require 'autoload.php';
Database::initDb();

if (isset($_POST['json'])) {
    $data = json_decode($_POST['json'], true);

    foreach ($data['city'] as $city) {
        $cityId = CityManager::create($city['name']);

        foreach ($city['districts'] as $district) {
            DistrictManager::create($cityId, $district);
        }
    }
}

?>
<form method="post">
    <textarea name="json"></textarea>
    <br>
    <input type="submit">
</form>