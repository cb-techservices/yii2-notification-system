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
	 /**
     * @var string The URL for the poll() for new notifications controller action
     */
	public $pollUrl = '/notifications/notifications/poll';
	
	/**
     * @var string The URL for the controller action that marks an individual notification as read
     */
	public $markAsReadUrl = '/notifications/notifications/read';
	
	/**
     * @var string The URL for the controller action that marks an individual notification as unread
     */
	public $markAsUnreadUrl = '/notifications/notifications/unread';
	
	/**
     * @var string The URL for the controller action that marks an individual notification as having been flashed
     */
	public $flashUrl = '/notifications/notifications/flash';
	
	/**
     * @var string The URL for the controller action that marks all notifications as read
     */
	public $readAllUrl = '/notifications/notifications/read-all';
	
	/**
     * @var string The URL for the controller action that marks all notifications as unread
     */
	public $unreadAllUrl = '/notifications/notifications/unread-all';
	
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
     * @var string The jQuery selector for the Mark All as Read button
     */
    public $markAllReadSelector = null;
    
    /**
     * @var string The jQuery selector for the Mark All as Unread button
     */
    public $markAllUnreadSelector = null;

    /**
     * @var string The jQuery selector in which the notifications list should
     *             be rendered
     */
    public $listSelector = null;
    
    /**
     * @var string The jQuery selector for the View All button
     */
    public $viewAllSelector = null;
    
    /**
     * @var string The jQuery selector for the View Unread button
     */
    public $viewUnreadSelector = null;
    
    /**
     * @var string The jQuery selector for the Notifications header view
     */
    public $headerSelector = null;
    
    /**
     * @var string The list item HTML template
     */
    public $listItemTemplate = null;
    
    /**
     * @var string The header HTML template
     */
    public $headerTemplate = null;
    
    /**
     * @var string The header title
     */
    public $headerTitle = "Notifications";
	

    public function init()
    {
        parent::init();
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
			
        //Set basic params
        $params = [
            'xhrTimeout' => Html::encode($this->xhrTimeout),
            'delay' => Html::encode($this->delay),
            'options' => $this->clientOptions,
            'pollInterval' => Html::encode($this->pollInterval),
            'counters' => $this->counters,
        ];

        //Set the URLs
		$params['pollUrl'] = $this->pollUrl;
        $params['markAsReadUrl'] = $this->markAsReadUrl;
        $params['markAsUnreadUrl'] = $this->markAsUnreadUrl;
		$params['flashUrl'] = $this->flashUrl;
		$params['readAllUrl'] = $this->readAllUrl;
		$params['unreadAllUrl'] = $this->unreadAllUrl;
		
//         if ($this->theme) {
//             $params['theme'] = Html::encode($this->theme);
//         }

		//Set the jQuery Selectors
        if ($this->markAllReadSelector) {
            $params['markAllReadSelector'] = $this->markAllReadSelector;
        }
        if ($this->markAllUnreadSelector) {
            $params['markAllUnreadSelector'] = $this->markAllUnreadSelector;
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
        
        if($this->viewAllSelector){
        		$params["viewAllSelector"] = $this->viewAllSelector;
        }
        
        if($this->viewUnreadSelector){
        		$params["viewUnreadSelector"] = $this->viewUnreadSelector;
        }
        
        if($this->headerSelector){
        		$params["headerSelector"] = $this->headerSelector;
        		if($this->headerTemplate){
        			$params["headerTemplate"] = $this->headerTemplate;
        		}
        }
        
        if($this->headerTitle){
        		$params["headerTitle"] = $this->headerTitle;
        }
        
        $js = 'var notificationSystem = Notifications(' . Json::encode($params,JSON_PRETTY_PRINT) . ');
notificationSystem.poll(0);';
        $view->registerJs($js);
    }
}