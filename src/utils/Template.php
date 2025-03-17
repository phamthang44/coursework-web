<?php

namespace utils;

class Template
{
    public static function header()
    {
        require_once __DIR__ . '/../views/layouts/header.php';
    }
    public static function footer()
    {
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    public static function postCard()
    {
        require_once __DIR__ . '/../views/posts/post-card.php';
    }
}
