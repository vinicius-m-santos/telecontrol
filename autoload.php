<?php

function autoloader($relativeFilePath) {
    $relativeFilePath = prepareClasName($relativeFilePath);
    if (file_exists($relativeFilePath)) {
        include_once $relativeFilePath;
    }
}

function prepareClasName($relativeFilePath)
{
    return sprintf(
        '%s/%s.php',
        __DIR__, 
        str_replace(
            '\\', 
            '/', 
            $relativeFilePath
        )
    );
}

spl_autoload_register('autoloader');