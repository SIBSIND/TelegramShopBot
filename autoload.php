<?php

function loader1($class)
{
    include '../' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
}

spl_autoload_register('loader1');