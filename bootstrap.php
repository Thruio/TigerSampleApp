<?php

require_once("vendor/autoload.php");

define("APP_ROOT", rtrim(__DIR__, "/"));
if (file_exists(APP_ROOT . '/env')) {
    define("APP_ENV", file_get_contents(APP_ROOT . '/env'));
}
