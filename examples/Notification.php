<?php

namespace common\models;

use Yii;
use cbtech\notification_system\models\NotificationBase;
use yii\helpers\Url;

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
class Notification extends NotificationBase
{	
	/**
	* @inheritdoc
	*/
	public function rules()
	{
        return array_merge(parent::rules(),[
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ]);
	}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUser()
    {
	    return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
	
	/**
     * A new comment notification
     */
    const KEY_NEW_COMMENT = 'new_comment';
    /**
     * A new connection notification
     */
    const KEY_NEW_CONNECTION_REQUEST = 'new_connection_request';

    /**
     * @var array Holds all usable notifications
     */
    public static $keys = [
        self::KEY_NEW_COMMENT,
    		self::KEY_NEW_CONNECTION_REQUEST
    ];

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        switch ($this->key) {
            case self::KEY_NEW_COMMENT:
                return Yii::t('app', '');

            case self::KEY_NEW_CONNECTION_REQUEST:
                return Yii::t('app', 'test');
        }
    }

    /**
     * @inheritdoc
     */
    public function getBody()
    {
        switch ($this->key) {
            case self::KEY_NEW_COMMENT:
                $comment = Comment::findOne($this->key_id);
                return Yii::t('app', 'You have a new comment from {user}', [
                    'user' => $comment->user->username
                ]);

            case self::KEY_NEW_CONNECTION_REQUEST:
				$user = User::findOne($this->user_id);
				$connection = User::findOne($this->key_id);
				$msg = Yii::t('app', '<b>{connection}</b> would like to connect with you.', [
                    'connection' => $connection->userProfile->name
                ]);
				$html = "<div class='' style=''>
							{$msg}
						</div>";
                return $html;

        }
    }
    
    /**
     * @inheritdoc
     */
    public function getFooter(){
    		switch ($this->key) {
            case self::KEY_NEW_COMMENT:
                return "";

            case self::KEY_NEW_CONNECTION_REQUEST:
				$html = "<div class='' style='text-align:right;'>
							<button class='btn btn-default btn-xs rejectConnection' data-keepOpenOnClick>Reject</button>
							<button class='btn btn-primary btn-xs acceptConnection' data-keepOpenOnClick>Accept</button>
						</div>";
                return $html;

        }
    }

    /**
     * @inheritdoc
     */
    public function getRoute()
    {
        switch ($this->key) {
            case self::KEY_NEW_COMMENT:
                return Url::to(['comment/index', 'id' => $this->key_id]);

            case self::KEY_NEW_CONNECTION_REQUEST:
                return Url::to(['user/user-connection-request', 'id' => $this->key_id]);
        };
    }
}