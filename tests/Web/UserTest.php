<?php

namespace SampleApp\Test\Web;

use TigerKit\Services\UserService;

class UserTest extends BaseWebTest
{
    public function testLoginPageAvailable()
    {
        $loginResponse = $this->doRequest("GET", "/login");
        $this->assertTrue($loginResponse->isOk());
    }

    public function testValidLogin()
    {
        $loginResponse = $this->doRequest("POST", "/login", ['username' => $this->testUserUsername, 'password' => $this->testUserPassword]);

      //$this->assertTrue($loginResponse->isOk());
        $this->assertEquals(302, $loginResponse->getStatus());
        $this->assertEquals("/dashboard", $loginResponse->header("location"));
    }

    public function testInvalidLogin()
    {
        $loginResponse = $this->doRequest("POST", "/login", ['username' => $this->testUserUsername, 'password' => "bogus"]);

      //$this->assertTrue($loginResponse->isOk());
        $this->assertEquals(302, $loginResponse->getStatus());
        $this->assertEquals("/login?failed", $loginResponse->header("location"));
    }

    public function testRegister()
    {
        $registerOpen = $this->doRequest("GET", "/register");
        $this->assertTrue($registerOpen->isOk());
    }

    public function testRegisterDo()
    {
        $password = $this->faker->password;
        $registerDo = $this->doRequest("POST", "/register", [
        "username" => $this->faker->userName,
        "realname" => $this->faker->name(),
        "password" => $password,
        "password2" => $password,
        "email" => $this->faker->safeEmail,
        ]);
        $this->assertEquals(302, $registerDo->getStatus());
        $this->assertEquals("/dashboard", $registerDo->header("location"));
    }

    public function testRegisterRejectUnmatchedPassword()
    {
        $password = $this->faker->password;
        $registerDo = $this->doRequest("POST", "/register", [
        "username" => $this->faker->userName,
        "realname" => $this->faker->name(),
        "password" => $password,
        "password2" => "doesntmatch",
        "email" => $this->faker->safeEmail,
        ]);
        $this->assertEquals(302, $registerDo->getStatus());
        $this->assertEquals("/register?failed=Passwords+do+not+match", $registerDo->header("location"));
    }

    public function testRegisterRejectShortPassword()
    {
        $password = "short";
        $registerDo = $this->doRequest("POST", "/register", [
        "username" => $this->faker->userName,
        "realname" => $this->faker->name(),
        "password" => $password,
        "password2" => $password,
        "email" => $this->faker->safeEmail,
        ]);
        $this->assertEquals(302, $registerDo->getStatus());
        $this->assertEquals("/register?failed=Password+has+to+be+atleast+6+characters", $registerDo->header("location"));
    }

    public function testRegisterRejectUserExists()
    {
        $userService = new UserService();
        $name = $this->faker->userName;
        $password = $this->faker->password;
        $collisionUser = $userService->createUser($name, $name, $name, "{$name}@example.org");
        $registerDo = $this->doRequest("POST", "/register", [
        "username" => $name,
        "realname" => $this->faker->name(),
        "password" => $password,
        "password2" => $password,
        "email" => $this->faker->safeEmail,
        ]);
        $collisionUser->delete();
        $this->assertEquals(302, $registerDo->getStatus());
        $this->assertEquals("/register?failed=Username+in+use.", $registerDo->header("location"));
    }

    public function testRegisterRejectBogusEmail()
    {
        $password = $this->faker->password;
        $email = "bogus.not.an.email";
        $registerDo = $this->doRequest("POST", "/register", [
        "username" => $this->faker->userName,
        "realname" => $this->faker->name(),
        "password" => $password,
        "password2" => $password,
        "email" => $email,
        ]);
        $this->assertEquals(302, $registerDo->getStatus());
        $this->assertEquals("/register?failed=Email+address+invalid", $registerDo->header("location"));
    }

    public function testLogout()
    {
        $loginResponse = $this->doRequest("POST", "/login", ['username' => $this->testUserUsername, 'password' => $this->testUserPassword]);
        $this->assertEquals(302, $loginResponse->getStatus());
        $this->assertEquals("/dashboard", $loginResponse->header("location"));

        $logoutResponse = $this->doRequest("GET", "/logout");
        $this->assertEquals(302, $logoutResponse->getStatus());
        $this->assertEquals("/login", $logoutResponse->header("location"));
    }
}
