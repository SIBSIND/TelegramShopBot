<?php

function loader1($class)
{
    include $_SERVER['DOCUMENT_ROOT'] . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
}
function loader2($class)
{
    include $_SERVER['DOCUMENT_ROOT'] . '/TelegarmShopBot/classes/' . str_replace('TelegramShopBot\\', '', $class) . '.php';
}

spl_autoload_register('loader1');
spl_autoload_register('loader2');