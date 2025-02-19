<?php

namespace App\Actions\Home;

class DisplayHome
{
    public function __invoke()
    {
        $accept = request_header('Accept');
        if ($accept === 'application/json') {
            render(200, json_out(['message' => 'Hello World']));
        } else {
            render(200, html_out(template(APP_ROOT . "/templates/pages/home.php", [])));
        }
    }
}