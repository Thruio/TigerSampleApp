<?php

namespace SampleApp\Test\Storage;

use SampleApp\Services\ImageService;
use TigerKit\Models\Image;
use TigerKit\Test\TigerBaseTest;

class ImageUploadTest extends TigerBaseTest
{

  /** @var ImageService */
  private $imageService;

  public function setUp()
  {
    parent::setUp();
    $this->imageService = new ImageService();
  }

  public function testUploadImage()
  {
    $mockAsset = APP_ROOT . "/tests/assets/sample-1.jpg";
    $mockAssetTmpLocation = tempnam(sys_get_temp_dir(), "test-");
    copy($mockAsset, $mockAssetTmpLocation);
    $mockUpload = [
      'name' => basename($mockAsset),
      'type' => "image/jpeg",
      'tmp_name' => $mockAssetTmpLocation,
      'error' => 0,
      'size' => filesize($mockAsset),
    ];

    $image = $this->imageService->uploadImage($this->testUser, $mockUpload);
    $this->assertTrue($image instanceof Image);
    $this->assertEquals(2448, $image->width);
    $this->assertEquals(3264, $image->height);
    $this->assertGreaterThan(0, $image->file_id);
    $this->assertEquals($this->testUser->user_id, $image->user_id);
    $this->assertEquals("image/jpeg", $image->filetype);
    $this->assertEquals(filesize($mockAsset), $image->filesize);
    $this->assertGreaterThan(time() - 3, strtotime($image->created));
    $this->assertGreaterThan(time() - 3, strtotime($image->updated));
    return $image;
  }

  public function testGetAllImages()
  {
    $images = $this->imageService->getAllImages();
    $this->assertTrue(is_array($images));
    $this->assertGreaterThanOrEqual(1, count($images));
    $this->assertTrue(end($images) instanceof Image);

  }

  /**
   * @depends testUploadImage
   */
  public function testRetrieveImageData(Image $image)
  {
    $data = $image->getData();
    $this->assertEquals($image->filesize, strlen($data));
  }

  /**
   * @depends testUploadImage
   */
  public function testRetrieveImageDataStream(Image $image)
  {
    $stream = $image->getDataStream();
    $data = stream_get_contents($stream);
    $this->assertEquals($image->filesize, strlen($data));
  }

  /**
   * @depends testUploadImage
   */
  public function testReplaceImageData(Image $image){
    $mockAsset = APP_ROOT . "/tests/assets/sample-2.jpg";
    $image->putData(file_get_contents($mockAsset));
    $this->assertEquals(filesize($mockAsset), $image->filesize);
    return $image;
  }

  /**
   * @depends testReplaceImageData
   */
  public function testReplaceImageDataStream(Image $image){
    $mockAsset = APP_ROOT . "/tests/assets/sample-3.jpg";
    $image->putDataStream(fopen($mockAsset, 'r'));
    $this->assertEquals(filesize($mockAsset), $image->filesize);
  }

}
