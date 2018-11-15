<?php

namespace cbtech\notification_system\models;

use Yii;
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
class Notification extends \yii\db\ActiveRecord
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
    * @return \yii\db\ActiveQuery
    */
//     public function getUser()
//     {
//     return $this->hasOne(User::className(), ['id' => 'user_id']);
//     }

	
	/**
     * A new message notification
     */
    const KEY_NEW_MESSAGE = 'new_message';
    /**
     * A meeting reminder notification
     */
    const KEY_MEETING_REMINDER = 'meeting_reminder';
    /**
     * No disk space left !
     */
    const KEY_NO_DISK_SPACE = 'no_disk_space';

    /**
     * @var array Holds all usable notifications
     */
    public static $keys = [
        self::KEY_NEW_MESSAGE,
        self::KEY_MEETING_REMINDER,
        self::KEY_NO_DISK_SPACE,
    ];

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        switch ($this->key) {
            case self::KEY_MEETING_REMINDER:
                return Yii::t('app', 'Meeting reminder');

            case self::KEY_NEW_MESSAGE:
                return Yii::t('app', 'You got a new message');

            case self::KEY_NO_DISK_SPACE:
                return Yii::t('app', 'No disk space left');
        }
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        switch ($this->key) {
            case self::KEY_MEETING_REMINDER:
                $meeting = Meeting::findOne($this->key_id);
                return Yii::t('app', 'You are meeting with {customer}', [
                    'customer' => $meeting->customer->name
                ]);

            case self::KEY_NEW_MESSAGE:
                $message = Message::findOne($this->key_id);
                return Yii::t('app', '{customer} sent you a message', [
                    'customer' => $meeting->customer->name
                ]);

            case self::KEY_NO_DISK_SPACE:
                // We don't have a key_id here
                return 'Please buy more space immediately';
        }
    }

    /**
     * @inheritdoc
     */
    public function getRoute()
    {
        switch ($this->key) {
            case self::KEY_MEETING_REMINDER:
                return ['meeting', 'id' => $this->key_id];

            case self::KEY_NEW_MESSAGE:
                return ['message/read', 'id' => $this->key_id];

            case self::KEY_NO_DISK_SPACE:
                return 'https://aws.amazon.com/';
        };
    }
}