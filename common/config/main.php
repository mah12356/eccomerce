<?php
return [
    'language' => 'fa-IR',
    'timeZone' => 'Asia/Tehran',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@upload' => dirname(dirname(__DIR__)) . '/upload',
        '@frontendWeb' => dirname(dirname(__DIR__)) . '/frontend/web',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            // uncomment if you want to cache RBAC items hierarchy
            // 'cache' => 'cache',
        ],
    ],
];
