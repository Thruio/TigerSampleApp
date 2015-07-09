<?php

namespace SampleApp\Test\Web;

use TigerKit\Models\User;

class IndexTest extends BaseWebTest
{

    protected $testUserUsername;
    protected $testUserPassword;
  /** @var User */
    protected $testUser;

    public function testHomeLoggedOut()
    {
        $response = $this->doRequest("GET", "/");
        $this->assertTrue($response->isOk());
    }

    public function testHomeLoggedIn()
    {
        $loginResponse = $this->doRequest("POST", "/login", ['username' => $this->testUserUsername, 'password' => $this->testUserPassword]);

      //$this->assertTrue($loginResponse->isOk());
        $this->assertEquals(302, $loginResponse->getStatus());
        $this->assertEquals("/dashboard", $loginResponse->header("location"));

        $homepageRedirectResponse = $this->doRequest("GET", "/");
        $this->assertEquals(302, $homepageRedirectResponse->getStatus());
        $this->assertEquals("/dashboard", $homepageRedirectResponse->header("location"));

    }
}
