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
        'https://unpkg.com/aos@2.3.1/dist/aos.css',
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css',
        'assets/vendor/bootstrap-icons/bootstrap-icons.css',
        'assets/vendor/boxicons/css/boxicons.min.css',
        'assets/vendor/glightbox/css/glightbox.min.css',
        'assets/vendor/remixicon/remixicon.css',
        'assets/vendor/swiper/swiper-bundle.min.css',
        'assets/css/style.css?v=221113.4',
        'css/site.css?v=221113.2',
    ];
    public $js = [
        'https://unpkg.com/aos@2.3.1/dist/aos.js',
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.vi.min.js',
        // 'https://cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.19/jquery.touchSwipe.min.js',
        'assets/vendor/glightbox/js/glightbox.min.js',
        'assets/vendor/isotope-layout/isotope.pkgd.min.js',
        'assets/vendor/swiper/swiper-bundle.min.js',
        'assets/vendor/waypoints/noframework.waypoints.js',
        'assets/js/main.js',
        'js/app.js?v=221108.1',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        // 'yii\jui\JuiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
