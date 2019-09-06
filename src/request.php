<?php
function request_header(string $header): string
{
    $headers = getallheaders();
    if (key_exists($header, $headers)) {
        return $headers[$header];
    }

    return "";
}

function accept(string $content_type): bool
{
    return strpos(request_header('Accept'), $content_type) !== false;
}

function request_body(): string
{
    return file_get_contents("php://input");
}

function json_in(): array
{
    return json_decode(request_body(), true);
}
