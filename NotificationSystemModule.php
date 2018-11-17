<?php

// namespace frontend\modules\unsplash;
namespace cbtech\notification_system;

use Yii;
use yii\base\Exception;
use yii\db\Expression;
use cbtech\notification_system\models\NotificationBase;

/**
 * unsplash module definition class
 */
class NotificationSystemModule extends \yii\base\Module
{
	/**
     * @var string The controllers namespace
     */
    public $controllerNamespace = 'cbtech\notification_system\controllers';
    /**
     * @var Notification The notification class defined by the application
     */
    public $notificationClass;
    /**
    * @var boolean Whether notification can be duplicated (same user_id, key, and key_id) or not
    */
    public $allowDuplicate = false;
    /**
     * @var string Database created_at field format
     */
    public $dbDateFormat = 'Y-m-d H:i:s';
    /**
     * @var callable|integer The current user id
     */
    public $userId;
	/**
	 * @var callable|integer The current user id
	 */
	public $expirationTime = 0;
	/**
     * @inheritdoc
     */
    public function init() {
        if (is_callable($this->userId)) {
            $this->userId = call_user_func($this->userId);
        }
        parent::init();
	    if (Yii::$app instanceof \yii\console\Application) {
		    $this->controllerNamespace = 'cbtech\notification_system\commands';
	    }
    }
    /**
     * Creates a notification
     *
     * @param Notification $notification The notification class
     * @param string $key The notification key
     * @param integer $user_id The user id that will get the notification
     * @param string $key_id The key unique id
     * @param string $type The notification type
     * @return bool Returns TRUE on success, FALSE on failure
     * @throws Exception
     */
    public static function notify($notification, $key, $user_id, $key_id = null, $type = Notification::TYPE_DEFAULT)
    {
        if (!in_array($key, $notification::$keys)) {
            throw new Exception("Not a registered notification key: $key");
        }
        if (!in_array($type, NotificationBase::$types)) {
            throw new Exception("Unknown notification type: $type");
        }
        /** @var Notification $instance */
        $instance = $notification::findOne(['user_id' => $user_id, 'key' => $key, 'key_id' => (string)$key_id]);
        if (!$instance || \Yii::$app->getModule('notifications')->allowDuplicate) {
            $instance = new $notification([
                'key' => $key,
                'type' => $type,
                'read' => 0,
                'flashed' => 0,
                'user_id' => $user_id,
                'key_id' => (string)$key_id,
                'created_at' => new Expression('NOW()'),
            ]);
            return $instance->save();
        }
        return true;
    }
}
