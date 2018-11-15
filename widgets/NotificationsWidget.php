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
    public $message;
    public $search;
    public $page;
    public $per_page;
    public $orientation;
    public $button_text;
    public $button_class;
    public $button_style;

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
			$params = [];
//         $params = [
//             'url' => Url::to(['/notifications/notifications/poll']),
//             'xhrTimeout' => Html::encode($this->xhrTimeout),
//             'delay' => Html::encode($this->delay),
//             'options' => $this->clientOptions,
//             'pollSeen' => !!$this->pollSeen,
//             'pollInterval' => Html::encode($this->pollInterval),
//             'counters' => $this->counters,
//         ];
//         if ($this->theme) {
//             $params['theme'] = Html::encode($this->theme);
//         }
//         if ($this->markAllSeenSelector) {
//             $params['markAllSeenSelector'] = $this->markAllSeenSelector;
//             $params['seenAllUrl'] = Url::to(['/notifications/notifications/read-all']);
//         }
//         if ($this->deleteAllSelector) {
//             $params['deleteAllSelector'] = $this->deleteAllSelector;
//             $params['deleteAllUrl'] = Url::to(['/notifications/notifications/delete-all']);
//         }
//         if ($this->listSelector) {
//             $params['seenUrl'] = Url::to(['/notifications/notifications/read']);
//             $params['deleteUrl'] = Url::to(['/notifications/notifications/delete']);
//             $params['flashUrl'] = Url::to(['/notifications/notifications/flash']);
//             $params['listSelector'] = $this->listSelector;
//             if ($this->listItemTemplate) {
//                 $params['listItemTemplate'] = $this->listItemTemplate;
//             }
//             if ($this->listItemBeforeRender instanceof JsExpression) {
//                 $params['listItemBeforeRender'] = $this->listItemBeforeRender;
//             }
//         }
        $js = 'var notificationSystem = Notifications(' . Json::encode($params) . ');notificationSystem.notify();';
        $view->registerJs($js);
    }
}