<?php

use App\Actions\Home\DisplayHome;

router(function () {
    use_request_uri();

    route(method(GET), url_path('/'), DisplayHome::class function () {

    });

});
