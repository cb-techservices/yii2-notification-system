<?php 

// namespace frontend\modules\unsplash;
namespace cbtech\notification_system\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use cbtech\notification_system\assets\NotificationAsset;
use yii\base\Exception;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\Json;

class NotificationsWidget extends Widget
{
	public $pollUrl = '/notifications/notifications/poll';
	public $markAsReadUrl = '/notifications/notifications/read';
	public $deleteUrl = '/notifications/notifications/delete';
	public $flashUrl = '/notifications/notifications/flash';
	
	/**
     * @var array additional options to be passed to the notification library.
     * Please refer to the plugin project page for available options.
     */
    public $clientOptions = [];
//     /**
//      * @var string the library name to be used for notifications
//      * One of the THEME_XXX constants
//      */
//     public $theme = null;
    /**
     * @var integer The time to leave the notification shown on screen
     */
    public $delay = 5000;
    /**
     * @var integer the XHR timeout in milliseconds
     */
    public $xhrTimeout = 2000;
    /**
     * @var integer The delay between pulls
     */
    public $pollInterval = 5000;
     /**
     * @var array An array of jQuery selector to be updated with the current
     *            notifications count
     */
    public $counters = [];
     /**
     * @var string A jQuery selector on which click mark all seen event
     *             will be fired
     */
    public $markAllSeenSelector = null;
    public $seenAllUrl = '/notifications/notifications/read-all';
    /**
     * @var string A jQuery selector on which click delete all event
     *             will be fired
     */
    public $deleteAllSelector = null;
    public $deleteAllUrl = '/notifications/notifications/delete-all';
    /**
     * @var string The jQuery selector in which the notifications list should
     *             be rendered
     */
    public $listSelector = null;
    /**
     * @var string The list item HTML template
     */
    public $listItemTemplate = null;
	

    public function init()
    {
        parent::init();
        
        //Load AppAssets
//         AppAsset::register($this->view);
    }

	/**
     * @inheritdoc
     */
    public function run()
    {
//         if (!isset($this->timeAgoLocale)) {
//             $this->timeAgoLocale = Yii::$app->language;
//         }
        $this->registerAssets();
    }
    

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        NotificationAsset::register($view);
        // Register the theme assets
//         if (!is_null($this->theme)) {
//             if (!in_array($this->theme, self::$_builtinThemes)) {
//                 throw new Exception("Unknown theme: " . $this->theme, 501);
//             }
//             foreach (['js' => 'registerJsFile', 'css' => 'registerCssFile'] as $type => $method) {
//                 $filename = NotificationAsset::getFilename($this->theme, $type);
//                 if ($filename) {
//                     $view->$method($asset->baseUrl . '/' . $filename, [
//                         'depends' => NotificationAsset::className()
//                     ]);
//                 }
//             }
//         }
//         // Register timeago i18n file
//         if ($filename = NotificationAsset::getTimeAgoI18n($this->timeAgoLocale)) {
//             $view->registerJsFile($asset->baseUrl . '/' . $filename, [
//                 'depends' => NotificationAsset::className()
//             ]);
//         }
			
        $params = [
            'pollUrl' => $this->pollUrl,
            'xhrTimeout' => Html::encode($this->xhrTimeout),
            'delay' => Html::encode($this->delay),
            'options' => $this->clientOptions,
//             'pollSeen' => $this->pollSeen,
            'pollInterval' => Html::encode($this->pollInterval),
            'counters' => $this->counters,
        ];
        
        $params['markAsReadUrl'] = $this->markAsReadUrl;
	    $params['deleteUrl'] = $this->deleteUrl;
		$params['flashUrl'] = $this->flashUrl;
//         if ($this->theme) {
//             $params['theme'] = Html::encode($this->theme);
//         }
        if ($this->markAllSeenSelector) {
            $params['markAllSeenSelector'] = $this->markAllSeenSelector;
            $params['seenAllUrl'] = $this->seenAllUrl;
        }
        if ($this->deleteAllSelector) {
            $params['deleteAllSelector'] = $this->deleteAllSelector;
            $params['deleteAllUrl'] = $this->deleteAllUrl;
        }
        if ($this->listSelector) {
            $params['listSelector'] = $this->listSelector;
            if ($this->listItemTemplate) {
                $params['listItemTemplate'] = $this->listItemTemplate;
            }
//             if ($this->listItemBeforeRender instanceof JsExpression) {
//                 $params['listItemBeforeRender'] = $this->listItemBeforeRender;
//             }
        }
        $js = 'var notificationSystem = Notifications(' . Json::encode($params,JSON_PRETTY_PRINT) . ');
notificationSystem.poll();';
        $view->registerJs($js);
    }
}