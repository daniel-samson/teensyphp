<?php

namespace App\Actions\Home;

/**
 * DisplayHome action - displays the home page
 */
class DisplayHome
{
    /**
     * @return void
     */
    public function __invoke()
    {
        $accept = request_header('Accept');
        if ($accept === 'application/json') {
            render(200, json_out(['message' => 'Hello World']));
        } else {
            render(200, html_out(template(app_root() . "/templates/pages/home.php", [])));
        }
    }
}