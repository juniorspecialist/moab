<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic,300,300italic,600,600italic&subset=latin,cyrillic',
        'css/bootstrap-editable.css',
    ];
//    public $js = [
//    ];
//    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
//    ];

    // script
    public $js = ['js/bootbox.min.js', 'js/main.js', 'js/Validation.js','js/jquery.validationEngine-ru.js','js/bootstrap-editable.min.js'];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
