<?php

namespace cbtech\notification_system\assets;

use yii\web\AssetBundle;

/**
 * Class ToastrAsset
 * 
 */
class TimeagoAsset extends AssetBundle
{
    /** @var string  */
    public $sourcePath = '@vendor/rmm5t/jquery-timeago';
    /** @var array $css */
    public $css = [

    		
    ];
    /** @var array $js */
    public $js = [
        'jquery.timeago.js',
    		'locales/jquery.timeago.en-short.js'
    ];
    /** @var array $depends */
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}