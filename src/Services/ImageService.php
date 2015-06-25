<?php
namespace SampleApp\Services;

use TigerKit\Models;

class ImageService extends BaseService {
  /**
   * @return Models\Image[]
   */
  public function getAllImages() {
    return Models\Image::search()->where('deleted', "No")->exec();
  }

  /**
   * @param Models\User $user
   * @param $uploadFile
   * @return Models\Image
   */
  public function uploadImage(Models\User $user, $uploadFile) {
    $image = Models\Image::CreateFromUpload($uploadFile);
    $image->user_id = $user->user_id;
    $image->save();
    return $image;
  }
}
