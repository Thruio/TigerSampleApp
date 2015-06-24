<?php
/** @var $app \Slim\Slim */
use SampleApp\Controllers;

# Index page.
\TigerKit\TigerApp::getSlimApp()->get('/', '\SampleApp\Controllers\IndexController:index');

# User Auth
\TigerKit\TigerApp::getSlimApp()->get('/login', '\SampleApp\Controllers\UserController:showLogin');
\TigerKit\TigerApp::getSlimApp()->post('/login', '\SampleApp\Controllers\UserController:doLogin');
\TigerKit\TigerApp::getSlimApp()->get('/register', '\SampleApp\Controllers\UserController:showRegister');
\TigerKit\TigerApp::getSlimApp()->post('/register', '\SampleApp\Controllers\UserController:doRegister');
\TigerKit\TigerApp::getSlimApp()->get('/logout', '\SampleApp\Controllers\UserController:logout');

# Dashboard
\TigerKit\TigerApp::getSlimApp()->get('/dashboard', '\SampleApp\Controllers\DashboardController:dashboard');

# Gallery
\TigerKit\TigerApp::getSlimApp()->get('/gallery', '\SampleApp\Controllers\GalleryController:showList');
\TigerKit\TigerApp::getSlimApp()->get('/gallery/upload', '\SampleApp\Controllers\GalleryController:showUpload');
\TigerKit\TigerApp::getSlimApp()->post('/gallery/upload', '\SampleApp\Controllers\GalleryController:doUpload');