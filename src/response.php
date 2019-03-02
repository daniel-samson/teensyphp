<?php

/**
 * render content to the client
 * @param int $http_code eg 200
 * @param string $content
 */
function render(int $http_code, string $content): void
{
    http_response_code($http_code);
    echo $content;
}

/**
 * configure content
 * @param string $type
 * @param string $content
 * @return string
 */
function content(string $type, string $content): string
{
    header('Content-Type: ' . $type, true);
    return $content;
}

/**
 * configure and encode to json
 * @param array $array
 * @return string
 */
function json_out(array $array): string
{
    return content(JSON_CONTENT, json_encode($array));
}

