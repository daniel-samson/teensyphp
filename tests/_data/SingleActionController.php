<?php

class SingleActionController
{
    public function __invoke()
    {
        echo json_encode(["message" => "Hello World"]);
    }
}