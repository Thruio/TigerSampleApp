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
    } elseif ($user->checkPassword($password)) {
      $_SESSION['user'] = $user;
      $this->slim->redirect("/dashboard");
      exit;
    } else {
      TigerApp::log("Failed login for {$username}", Log::WARN);
    }
    $this->slim->redirect("/login?failed");
    exit;
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
    if ($_POST['password'] !== $_POST['password2']) {
      header("Location: register?failed=" . urlencode("Passwords do not match"));
      exit;
    }

    if (Models\User::search()->where('username', $_POST['username'])->count() > 0) {
      header("Location: register?failed=" . urlencode("Username in use."));
      exit;
    }

    if (strlen($_POST['password']) < 6) {
      header("Location: register?failed=" . urlencode("Password has to be atleast 6 characters"));
      exit;
    }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      header("Location: register?failed=" . urlencode("Email address invalid"));
      exit;
    }

    $user = new Models\User();
    $user->username = $_POST['username'];
    $user->displayname = $_POST['realname'];
    $user->setPassword($_POST['password']);
    $user->created = date("Y-m-d H:i:s");
    $user->email = $_POST['email'];
    $user->save();
    $_SESSION['user'] = $user;
    $this->slim->redirect("/dashboard");
    exit;
  }

  public function logout()
  {
    $user = Models\User::getCurrent();
    if ($user instanceof Models\User) {
      TigerApp::log("Logout for {$user->username}");
    }

    Session::dispose('user');
    header("Location: login");
    exit;
  }
}