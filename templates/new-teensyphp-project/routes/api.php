<?php
routerGroup("/api", function () {
    route(method("GET"), url_path("/"), function () {
        render(200, json_out(["message" => "Hello World!"]));
    });
});