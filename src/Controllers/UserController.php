<?php
namespace SampleApp\Controllers;

use TigerKit\Services\UserService;
use Slim\Log;
use TigerKit\Models;
use TigerKit\TigerApp;
use Thru\Session\Session;

class UserController extends BaseController
{
    public function showLogin()
    {
        $this->slim->render(
            'user/login.phtml', array(
            'no_wrap' => true,
            'page_title' => 'Login'
            )
        );
    }

    public function doLogin()
    {
        $userService = new UserService();
        $username = $this->slim->request()->post('username');
        $password = $this->slim->request()->post('password');
        if ($userService->doLogin($username, $password)) {
            $this->slim->response()->redirect("/dashboard");
        } else {
            $this->slim->response()->redirect("/login?failed");
        }
    }

    public function showRegister()
    {
        $view = $this->slim->view();
        $view->addJS("assets/js/register/validate.js");

        $this->slim->render(
            'user/register.phtml', array(
            'no_wrap' => false,
            'page_title' => 'Register'
            )
        );
    }

    public function doRegister()
    {
        if ($this->slim->request()->post('password') !== $this->slim->request()->post('password2')) {
            $this->slim->response()->redirect("/register?failed=" . urlencode("Passwords do not match"));
        } elseif (Models\User::search()->where('username', $this->slim->request()->post('username'))->count() > 0) {
            $this->slim->response()->redirect("/register?failed=" . urlencode("Username in use."));
        } elseif (strlen($this->slim->request()->post('password')) < 6) {
            $this->slim->response()->redirect("/register?failed=" . urlencode("Password has to be atleast 6 characters"));
        } elseif (!filter_var($this->slim->request()->post('email'), FILTER_VALIDATE_EMAIL)) {
            $this->slim->response()->redirect("/register?failed=" . urlencode("Email address invalid"));
        } else {
            $userService = new UserService();
            $user = $userService->createUser(
                $this->slim->request()->post('username'),
                $this->slim->request()->post('realname'),
                $this->slim->request()->post('password'),
                $this->slim->request()->post('email')
            );
            Session::set("user", $user);
            $this->slim->response()->redirect("/dashboard");
        }
    }

    public function logout()
    {
        $user = Models\User::getCurrent();
        if ($user instanceof Models\User) {
            TigerApp::log("Logout for {$user->username}");
        }

        Session::dispose('user');
        $this->slim->response()->redirect("/login");
    }
}
