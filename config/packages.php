<?php

return [
    'article' => [
        'base_path'         => base_path('packages/foxytouch/article'),
        'service_provider'  => Foxytouch\Article\ArticleServiceProvider::class,
    ],
    'user' => [
        'base_path'         => base_path('packages/foxytouch/user'),
        'service_provider'  => Foxytouch\User\UserServiceProvider::class,
    ],
];
