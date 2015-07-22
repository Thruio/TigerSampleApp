<?php
namespace SampleApp\Controllers;

use TigerKit\Models;
use TigerKit\TigerApp;
use TigerKit\TigerView;

class BaseController extends \TigerKit\BaseController
{
    public function __construct()
    {
        parent::__construct();
        /**
 * @var TigerView $view 
*/
        $view = $this->slim->view();
        $view->setSiteTitle(TigerApp::Config("Application Name"));
        $view->addCSS("vendor/twbs/bootstrap/dist/css/bootstrap.min.css");
        $view->addCSS("assets/css/navbar.css");
    }
}
