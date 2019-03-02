<?php
function request_header(string $header): string
{
    $headers = getallheaders();
    if (key_exists($header, $headers)) {
        return $headers[$header];
    }

    return "";
}

function request_body(): string
{
    return file_get_contents("php://input");
}

function json_in(): array
{
    return json_decode(request_body(), true);
}
