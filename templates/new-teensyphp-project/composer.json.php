{
    "name": "<?=$vender?>/<?=$project_dirname?>",
    "description": "A new TeensyPHP Project",
    "minimum-stability": "stable",
    "license": "MIT",
    "authors": [
        {
            "name": "<?=$author?>",
            "email": "<?=$email?>"
        }
    ],
    "require": {
        "php": ">=8.0",
        "ext-pdo": "*",
        "ext-json": "*",
        "daniel-samson/teensyphp": "^1"
    },
    "autoload": {
        "psr-4": {
            "App\\": "App/"
        }
    }
}
