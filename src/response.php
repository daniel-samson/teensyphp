<?php

/** Content Types */
const ATOM_CONTENT = 'application/atom+xml';
const CSS_CONTENT = 'text/css';
const JAVASCRIPT_CONTENT = 'text/javascript';
const JSON_CONTENT = 'application/json';
const PDF_CONTENT = 'application/pdf';
const TEXT_CONTENT = 'text/plain';
const HTML_CONTENT = 'text/html';
const XML_CONTENT = 'text/xml';

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

/**
 * configure and echo html content
 * @param string $content
 * @return string
 */
function html_out(string $content): string
{
    return content(HTML_CONTENT, $content);
}
