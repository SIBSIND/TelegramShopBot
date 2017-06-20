<?php

$settings = [
    // настройки для нескольких ботов в формате: 'имя_бота' => 'токен'
    'bots' => [
        'NAME' => 'TOKEN',
    ],

    'dbHost'     => 'localhost',       // Хост базы
    'dbDatabase' => 'TelegramShopBot', // Имя базы
    'dbUser'     => 'root',            // Пользователь
    'dbPassword' => '',                // Пароль

    'methodOfUpdating' => 1,           // Способ получения обновлений (0 - через постоянныйе запросы на telegram api; 1 - через вебхук)

    'webHookURL'           => 'https://localhost/TelegramShopBot/', // URL где находятся файлы бота (для вебхука)
    'pathToSSLCertificate' => 'SSLcertificate.pem',                // Путь до файла с ssl сертификатом (для вебхука)

    'qiwiNumber'   => '+71234567890', // Номер Qiwi
    'qiwiPassword' => '123456789',        // Пароль от Qiwi
    'btcNumber'    => '', // Номер Bitcoin (на данный момент не работает)
];