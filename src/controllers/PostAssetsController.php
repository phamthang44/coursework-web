<?php

namespace controllers;

use dal\PostAssetDAOImpl;

class PostAssetController
{
    private $postAssetDAO;
    public function __construct()
    {
        $this->postAssetDAO = new PostAssetDAOImpl();
    }

    public function getPostAssets($postId)
    {
        return $this->postAssetDAO->getByPostId($postId);
    }

    // public function getPostAsset($postId, $assetId)
    // {
    //     return $this->postAssetDAO->getPostAsset($postId, $assetId);
    // }

    public function createPostAsset($media_key, $post_id)
    {
        return $this->postAssetDAO->create($media_key, $post_id);
    }

    // public function updatePostAsset($postId, $assetId, $assetType, $assetUrl)
    // {
    //     return $this->postAssetDAO->updatePostAsset($postId, $assetId, $assetType, $assetUrl);
    // }

    public function deletePostAsset($assetId)
    {
        return $this->postAssetDAO->delete($assetId);
    }
}
