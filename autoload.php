<?php

function loader1($class)
{
    include $_SERVER['DOCUMENT_ROOT'] . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
}
function loader2($class)
{
    $class = str_replace('TelegramShopBot\\', '/TelegarmShopBot/classes/', $class);
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    include $_SERVER['DOCUMENT_ROOT'] . $class . '.php';
}

spl_autoload_register('loader1');
spl_autoload_register('loader2');