<?php

namespace SampleApp\Test\Web;

use TigerKit\Services\UserService;

class GalleryTest extends BaseWebTest
{
    public function testLoginPageAvailable()
    {
        $galleryResponse = $this->doRequest("GET", "/gallery");
        $this->assertTrue($galleryResponse->isOk());
    }

    public function testGalleryPageLoggedIn()
    {
        $loginResponse = $this->doRequest("POST", "/login", ['username' => $this->testUserUsername, 'password' => $this->testUserPassword]);
        $galleryResponse = $this->doRequest("GET", "/gallery");
        $this->assertTrue($galleryResponse->isOk());
    }

    public function testGalleryUploadLoggedOut()
    {
        $galleryResponse = $this->doRequest("GET", "/gallery/upload");
        $this->assertEquals(302, $galleryResponse->getStatus());
        $this->assertEquals("/login", $galleryResponse->header("location"));
    }

    public function testGalleryUploadLoggedIn()
    {
        $loginResponse = $this->doRequest("POST", "/login", ['username' => $this->testUserUsername, 'password' => $this->testUserPassword]);
        $galleryResponse = $this->doRequest("GET", "/gallery/upload");
        $this->assertTrue($galleryResponse->isOk());
    }

    public function testGalleryUploadLoggedInSend()
    {
        $loginResponse = $this->doRequest("POST", "/login", ['username' => $this->testUserUsername, 'password' => $this->testUserPassword]);

        $mockAssetPath = APP_ROOT . "/vendor/thru.io/tiger-kit/tests/Assets/sample-3.jpg";
        $mockAssetTmpLocation = tempnam(sys_get_temp_dir(), "test-");
        copy($mockAssetPath, $mockAssetTmpLocation);
        $mockUpload = [
        'name' => basename($mockAssetPath),
        'type' => "image/jpeg",
        'tmp_name' => $mockAssetTmpLocation,
        'error' => 0,
        'size' => filesize($mockAssetPath),
        ];

        $_FILES['file'] = $mockUpload;
        $galleryResponse = $this->doRequest("POST", "/gallery/upload");
        $this->assertTrue($galleryResponse->isOk());
    }

    public function testGalleryUploadLoggedOutSend()
    {
        $mockAssetPath = APP_ROOT . "/vendor/thru.io/tiger-kit/tests/Assets/sample-3.jpg";
        $mockAssetTmpLocation = tempnam(sys_get_temp_dir(), "test-");
        copy($mockAssetPath, $mockAssetTmpLocation);
        $mockUpload = [
        'name' => basename($mockAssetPath),
        'type' => "image/jpeg",
        'tmp_name' => $mockAssetTmpLocation,
        'error' => 0,
        'size' => filesize($mockAssetPath),
        ];

        $_FILES['file'] = $mockUpload;
        $galleryResponse = $this->doRequest("POST", "/gallery/upload");
        $this->assertEquals(404, $galleryResponse->getStatus());
    }
}
