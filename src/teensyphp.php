<?php

const GET = 'GET';
const HEAD = 'HEAD';
const POST = 'POST';
const PUT = 'PUT';
const DELETE = 'DELETE';
const CONNECT = 'CONNECT';
const OPTIONS = 'OPTIONS';
const TRACE = 'TRACE';

const ATOM_CONTENT = 'application/atom+xml';
const CSS_CONTENT = 'text/css';
const JAVASCRIPT_CONTENT = 'text/javascript';
const JSON_CONTENT = 'application/json';
const PDF_CONTENT = 'application/pdf';
const TEXT_CONTENT = 'text/plain';
const XML_CONTENT = 'text/xml';


function method(string $string)
{
    return $_SERVER['REQUEST_METHOD'] === $string;
}

function url_path(string $path): bool
{
    return $_SERVER['QUERY_STRING'] === $path;
}

function route(bool $http_method, bool $path, callable $method): void
{
    if (!$http_method || !$path) {
        return;
    }

    call_user_func_array($method, array());
    exit();
}

function render(int $http_code, string $content): void
{
    http_response_code($http_code);
    echo $content;
}

function content(string $type, string $content): string
{
    header('Content-Type: ' . $type, true);
    return $content;
}

function json_out(array $array): string
{
    return content(JSON_CONTENT, json_encode($array));
}

function header(string $header): string
{
    $headers = getallheaders();
    if (key_exists($header, $headers)) {
        return $headers[$header];
    }

    return "";
}

function body(): string
{
    return file_get_contents("php://input");
}

function json_in(): array
{
    return json_decode(body(), true);
}

function redirect(string $url): string
{
    header('Location: ' . $url, true);
    exit();
}