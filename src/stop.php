<?php
/**
 * Exits execution. If testing is running it returns the number instead
 */
function stop($code = 0) {
    if (! defined('PHPUNIT_COMPOSER_INSTALL') && ! defined('__PHPUNIT_PHAR__')) {
       exit($code);
    }

    return $code;
}