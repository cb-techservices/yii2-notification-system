<?php

namespace cbtech\notification_system\models;

use Yii;
use cbtech\notification_system\NotificationSystemModule;
// use common\models\User;

/**
 * This is the model class for table "notification".
*
    * @property integer $id
    * @property integer $user_id
    * @property string $key
    * @property integer $key_id
    * @property string $type
    * @property integer $read
    * @property integer $flashed
    * @property string $created_at
    * @property string $updated_at
    *
            * @property User $user
    */
abstract class NotificationBase extends \yii\db\ActiveRecord
{
	/**
	* @inheritdoc
	*/
	public static function tableName()
	{
		return 'notification';
	}
	
	/**
	* @inheritdoc
	*/
	public function rules()
	{
	        return [
	            [['user_id', 'key', 'type'], 'required'],
	            [['user_id', 'key_id', 'read', 'flashed'], 'integer'],
	            [['created_at', 'updated_at'], 'safe'],
	            [['key', 'type'], 'string', 'max' => 255],
// 	            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
	        ];
	}

	/**
	* @inheritdoc
	*/
	public function attributeLabels()
	{
		return [
		    'id' => 'ID',
		    'user_id' => 'User ID',
		    'key' => 'Key',
		    'key_id' => 'Key ID',
		    'type' => 'Type',
		    'read' => 'Read',
		    'flashed' => 'Flashed',
		    'created_at' => 'Created At',
		    'updated_at' => 'Updated At',
		];
	}

 	/**
     * Gets the notification title
     *
     * @return string
     */
    abstract public function getTitle();
    /**
     * Gets the notification body
     *
     * @return string
     */
    abstract public function getBody();
    /**
     * Gets the notification route
     *
     * @return string
     */
    abstract public function getRoute();

    /**
     * Default notification
     */
    const TYPE_DEFAULT = 'default';
    /**
     * Error notification
     */
    const TYPE_ERROR   = 'error';
    /**
     * Warning notification
     */
    const TYPE_WARNING = 'warning';
    /**
     * Success notification type
     */
    const TYPE_SUCCESS = 'success';
    /**
     * @var array List of all enabled notification types
     */
    public static $types = [
        self::TYPE_WARNING,
        self::TYPE_DEFAULT,
        self::TYPE_ERROR,
        self::TYPE_SUCCESS,
    ];
    
 	/**
     * Creates a notification
     *
     * @param string $key
     * @param integer $user_id The user id that will get the notification
     * @param string $key_id The foreign instance id
     * @param string $type
     * @return bool Returns TRUE on success, FALSE on failure
     * @throws \Exception
     */
    public static function notify($key, $user_id, $key_id = null, $type = self::TYPE_DEFAULT)
    {
        $class = self::className();
        return NotificationSystemModule::notify(new $class(), $key, $user_id, $key_id, $type);
    }
    /**
     * Creates a warning notification
     *
     * @param string $key
     * @param integer $user_id The user id that will get the notification
     * @param string $key_id The notification key id
     * @return bool Returns TRUE on success, FALSE on failure
     */
    public static function warning($key, $user_id, $key_id = null)
    {
        return static::notify($key, $user_id, $key_id, self::TYPE_WARNING);
    }
    /**
     * Creates an error notification
     *
     * @param string $key
     * @param integer $user_id The user id that will get the notification
     * @param string $key_id The notification key id
     * @return bool Returns TRUE on success, FALSE on failure
     */
    public static function error($key, $user_id, $key_id = null)
    {
        return static::notify($key, $user_id, $key_id, self::TYPE_ERROR);
    }
    /**
     * Creates a success notification
     *
     * @param string $key
     * @param integer $user_id The user id that will get the notification
     * @param string $key_id The notification key id
     * @return bool Returns TRUE on success, FALSE on failure
     */
    public static function success($key, $user_id, $key_id = null)
    {
        return static::notify($key, $user_id, $key_id, self::TYPE_SUCCESS);
    }
}