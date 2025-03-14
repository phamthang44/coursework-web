<?php

namespace models;

class PostAsset
{
    private $post_asset_id;
    private $media_key;
    private $post_id;

    // Constructor
    public function __construct($post_asset_id, $media_key, $post_id)
    {
        $this->post_asset_id = $post_asset_id;
        $this->media_key = $media_key;
        $this->post_id = $post_id;
    }

    // Getters
    public function getPostAssetId()
    {
        return $this->post_asset_id;
    }

    public function getMediaKey()
    {
        return $this->media_key;
    }

    public function getPostId()
    {
        return $this->post_id;
    }

    // Setters
    public function setMediaKey($media_key)
    {
        $this->media_key = $media_key;
    }

    public function setPostId($post_id)
    {
        $this->post_id = $post_id;
    }

    // Convert to array for easier database operations
    public function toArray(): array
    {
        return [
            'post_asset_id' => $this->post_asset_id,
            'media_key' => $this->media_key,
            'post_id' => $this->post_id
        ];
    }
}
