<?php
namespace SampleApp\Controllers;
use TigerKit\BaseController;
use TigerKit\Models;

class IndexController extends BaseController{


  public function index(){
      $user = Models\User::getCurrent();
      $this->slim->log->debug("Index page accessed from {$this->slim->request()->getIp()}");

      if($user instanceof Models\User){
        $this->slim->redirect("dashboard");
      }else {
        $this->slim->render('home/home.phtml', array());
      }
  }
}