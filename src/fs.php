<?php

/**
 * @return string the document root (where the index.php or public/index.php is located)
 */
function document_root(): string
{
    return $_SERVER['DOCUMENT_ROOT'];
}

/**
 * @return string the app root (where the app is located)
 */
function app_root(): string
{
    return dirname(document_root());
}