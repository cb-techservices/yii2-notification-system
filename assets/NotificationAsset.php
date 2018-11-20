<?php

namespace cbtech\notification_system\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class NotificationAsset extends AssetBundle
{
    public $basePath = '@cbtech/notification_system';
    public $sourcePath = '@cbtech/notification_system';
    public $css = [
		'css/notifications.css'
    ];
    public $js = [
    		'js/Notifications.js'
    ];
    public $jsOptions = [
    		"position"=>\yii\web\View::POS_HEAD
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    		'cbtech\notification_system\assets\ToastrAsset',
    		'yii\bootstrap\BootstrapPluginAsset'
    ];
    public $publishOptions = [
    		'forceCopy' => true,
    		'only'=>[
    			'js/*',
    			'css/*'
    		]
    ];
}
