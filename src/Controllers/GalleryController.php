<?php
namespace SampleApp\Controllers;

use TigerKit\Models;

class GalleryController extends BaseController
{
  public function showList()
  {
    $images = Models\Image::search()->exec();
    $this->slim->render('gallery/list.phtml', ['images' => $images]);
  }

  public function showUpload(){
    Models\User::checkLoggedIn();
    $this->slim->render('gallery/upload.phtml');
  }

  public function doUpload(){
    Models\User::checkLoggedIn();
    $image = Models\Image::CreateFromUpload($_FILES['file']);
    \Kint::dump(Models\User::getCurrent());
    $image->user_id = Models\User::getCurrent()->user_id;
    $image->save();
  }
}