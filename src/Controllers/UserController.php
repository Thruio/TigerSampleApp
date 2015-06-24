<?php
namespace SampleApp\Controllers;

use Slim\Log;
use TigerKit\Models;
use TigerKit\TigerApp;
use Thru\Session\Session;

class UserController extends BaseController
{
  public function showLogin()
  {
    $this->slim->render('user/login.phtml', array(
      'no_wrap' => true,
      'page_title' => 'Login'
    ));
  }

  public function doLogin()
  {
    $username = $this->slim->request()->post('username');
    $password = $this->slim->request()->post('password');

    // Support logging in with email address
    $user = Models\User::search()->where('email', $username)->execOne();

    // Support logging in with username
    if (!$user instanceof Models\User) {
      $user = Models\User::search()->where('username', $username)->execOne();
    }

    if (!$user instanceof Models\User) {
      TigerApp::log("No such user {$username}", Log::WARN);
      $this->slim->redirect("/login?failed");
    } elseif ($user->checkPassword($password)) {
      Session::set("user", $user);
      $this->slim->redirect("/dashboard");
    } else {
      TigerApp::log("Failed login for {$username}", Log::WARN);
      $this->slim->redirect("/login?failed");
    }
  }

  public function showRegister()
  {
    $this->slim->render('user/register.phtml', array(
      'no_wrap' => false,
      'page_title' => 'Register'
    ));
  }

  public function doRegister()
  {
    if ($this->slim->request()->post('password') !== $this->slim->request()->post('password2')) {
      $this->slim->redirect("register?failed=" . urlencode("Passwords do not match"));
      return false;
    }

    if (Models\User::search()->where('username', $this->slim->request()->post('username'))->count() > 0) {
      $this->slim->redirect("register?failed=" . urlencode("Username in use."));
      return false;
    }

    if (strlen($this->slim->request()->post('password')) < 6) {
      $this->slim->redirect("register?failed=" . urlencode("Password has to be atleast 6 characters"));
      return false;
    }

    if (!filter_var($this->slim->request()->post('email'), FILTER_VALIDATE_EMAIL)) {
      $this->slim->redirect("register?failed=" . urlencode("Email address invalid"));
      return false;
    }

    $user = new Models\User();
    $user->username = $this->slim->request()->post('username');
    $user->displayname = $this->slim->request()->post('realname');
    $user->setPassword($this->slim->request()->post('password'));
    $user->created = date("Y-m-d H:i:s");
    $user->email = $this->slim->request()->post('email');
    $user->save();
    Session::set("user", $user);
    $this->slim->redirect("/dashboard");
  }

  public function logout()
  {
    $user = Models\User::getCurrent();
    if ($user instanceof Models\User) {
      TigerApp::log("Logout for {$user->username}");
    }

    Session::dispose('user');
    $this->slim->redirect("/login");
  }
}