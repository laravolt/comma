<?php

/*
 * Set specific configuration variables here
 */
return [
    'route' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
        'prefix' => 'cms',
    ],
    'view' => [
        'layout' => 'ui::layouts.app',
    ],
    'menu' => [
        'enabled' => true,
    ],
    'models' => [
        'post' => \Laravolt\Comma\Models\Post::class,
        'tag' => \Laravolt\Comma\Models\Tag::class,
    ],
    'default_type' => 'post',
];
