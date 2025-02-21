<?php
use App\Actions\Home\DisplayHome;


routerGroup("/", function () {
    route(method(GET), url_path('/'), DisplayHome::class);
});
