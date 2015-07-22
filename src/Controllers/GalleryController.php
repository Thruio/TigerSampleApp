<?php
namespace SampleApp\Controllers;

use TigerKit\Services\ImageService;
use TigerKit\Models;

class GalleryController extends BaseController
{
    public function showList()
    {
        $imageService = new ImageService();
        $images = $imageService->getAllImages();
        \Kint::dump($images);
        $this->slim->render('gallery/list.phtml', ['images' => $images]);
    }

    public function showUpload()
    {
        Models\User::checkLoggedIn();
        $this->slim->render('gallery/upload.phtml');
    }

    public function doUpload()
    {
        Models\User::checkLoggedIn();
        $user = Models\User::getCurrent();
        if ($user instanceof Models\User) {
            $imageService = new ImageService();
            $imageService->uploadImage($user, $_FILES['file']);
        } else {
            $this->slim->notFound();
        }
    }
}
