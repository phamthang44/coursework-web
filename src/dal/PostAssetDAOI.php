<?php

namespace dal;

use models\PostAsset;

interface PostAssetDAOI
{
    public function getById($post_asset_id);
    public function getByPostId($post_id);
    public function create($media_key, $post_id);
    public function delete($post_asset_id);
    public function update($post_asset_id, $media_key);
    public function getLastPostAssetId();
    public function getByPostIds(array $postIds);
}
