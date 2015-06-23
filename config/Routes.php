<?php
/** @var $app \Slim\Slim */
use SampleApp\Controllers;
\TigerKit\TigerApp::getSlimApp()->get('/', '\SampleApp\Controllers\IndexController:index');
