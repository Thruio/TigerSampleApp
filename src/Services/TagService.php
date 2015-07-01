<?php
namespace SampleApp\Services;

use TigerKit\Models;

class TagService extends BaseService {

  /**
   * @param $tagName
   * @return false|Models\Tag
   */
  public function getTagByName($tagName){
    return Models\Tag::search()
      ->where('name', $tagName)
      ->where('deleted', 'No')
      ->where('hidden', 'No')
      ->execOne();
  }

  static public function CreateOrFind($tagName){
    $tagService = new TagService();
    $tag = $tagService->getTagByName($tagName);
    if(!$tag){
      $tag = new Models\Tag();
      $tag->name = $tagName;
      $tag->save();
    }
    return $tag;
  }
}
