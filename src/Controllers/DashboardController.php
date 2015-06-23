<?php
namespace SampleApp\Controllers;

use TigerKit\Models;

class DashboardController extends BaseController
{
  public function dashboard()
  {
    $user = Models\User::getCurrent();

    if ($user instanceof Models\User) {
      $this->slim->render('dashboard/dashboard.phtml', array());
    } else {
      $this->slim->redirect("/");
    }
  }
}