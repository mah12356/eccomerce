<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/main.css',
        'css/header.css',
        'css/footer.css',
        'css/bootstrap-icons.css',
        'css/swiper-bundle.min.css',
        'css/cards.css',
        'css/sliders.css',
    ];
    public $js = [
        'js/swiper-bundle.min.js',
        'js/header.js',
        'js/main.js',
        'js/sliders.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
        'yii\bootstrap5\BootstrapPluginAsset',
    ];
}
