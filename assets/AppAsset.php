<?php

namespace cbtech\notification_system\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@cbtech/notification_system';
    public $sourcePath = '@cbtech/notification_system';
    public $css = [
        
    ];
    public $js = [
//     		'js/spin.js',
    ];
    public $jsOptions = [
    		"position"=>\yii\web\View::POS_HEAD
    ];
    public $depends = [
//         'yii\web\YiiAsset',
    		'frontend\assets\jQueryAsset',
    ];
}
