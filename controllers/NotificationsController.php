<?php

// namespace frontend\modules\unsplash\controllers;
namespace cbtech\notification_system\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use cbtech\notification_system\models\Notification;


class NotificationsController extends Controller
{
    /**
     * @var integer The current user id
     */
    private $user_id;
    /**
     * @var string The notification class
     */
    private $notificationClass;
    /**
     * @inheritdoc
     */
    public function init()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $this->user_id = $this->module->userId;
        $this->notificationClass = $this->module->notificationClass;
// 		$this->notificationClass = Notification::className();
        parent::init();
    }
    /**
     * Poll action
     *
     * @param int $seen Whether to show already seen notifications
     * @return array
     */
    public function actionPoll($all = 0)
    {
//     		\Yii::error($this->notificationClass);
//         $read = $read ? 1 : 0;
//         \Yii::error($read);
        /** @var Notification $class */
        $class = $this->notificationClass;
        $models = $class::find()->where(['user_id' => $this->user_id]);
		if($all == 0){
        	$models->andWhere(['or', ["read"=>0], ['flashed'=>0]]);			
		}else{
			$models->andWhere(['or', ["read"=>0],["read"=>1], ['flashed'=>0]]);	
		}
		$models = $models->orderBy('read, created_at DESC')
						 ->all();
		
        $results = $this->convertModelsToArray($models);
//         \Yii::error(print_r($models,true));
        
//         \Yii::error(print_r($results,true));
        return $results;
    }
    /**
     * Marks a notification as read and redirects the user to the final route
     *
     * @param int $id The notification id
     * @return Response
     * @throws HttpException Throws an exception if the notification is not
     *         found, or if it don't belongs to the logged in user
     */
    public function actionRnr($id)
    {
        $notification = $this->actionRead($id);
        return $this->redirect(Url::to($notification->getRoute()));
    }
    /**
     * Marks a notification as read
     *
     * @param int $id The notification id
     * @return Notification The updated notification record
     * @throws HttpException Throws an exception if the notification is not
     *         found, or if it don't belongs to the logged in user
     */
    public function actionRead($id)
    {
        $notification = $this->getNotification($id);
        $notification->read = 1;
        $notification->save();
        return $notification;
    }
    /**
     * Marks all notification as read
     *
     * @throws HttpException Throws an exception if the notification is not
     *         found, or if it don't belongs to the logged in user
     */
    public function actionReadAll()
    {
        $notificationsIds = Yii::$app->request->post('ids', []);
        $notifications = [];
        foreach ($notificationsIds as $id) {
            $notification = $this->getNotification($id);
            $notification->read = 1;
            $notification->save();
            array_push($notifications, $notification);
        }
        return $this->convertModelsToArray($notifications);
    }
    
	public function actionUnread($id)
    {
        $notification = $this->getNotification($id);
        $notification->read = 0;
        $notification->save();
        return $notification;
    }
    /**
     * Unread all notifications
     *
     * @throws HttpException Throws an exception if the notification is not
     *         found, or if it don't belongs to the logged in user
     */
    public function actionUnreadAll()
    {
        $notificationsIds = Yii::$app->request->post('ids', []);
        $notifications = [];
        foreach ($notificationsIds as $id) {
            $notification = $this->getNotification($id);
            $notification->read = 0;
            $notification->save();
            array_push($notifications, $notification);
        }
        return $this->convertModelsToArray($notifications);
    }
    
    public function actionFlash($id)
    {
        $notification = $this->getNotification($id);
        $notification->flashed = 1;
        $notification->save();
        return $notification;
    }
    /**
     * Gets a notification by id
     *
     * @param int $id The notification id
     * @return Notification
     * @throws HttpException Throws an exception if the notification is not
     *         found, or if it don't belongs to the logged in user
     */
    private function getNotification($id)
    {
        /** @var Notification $notification */
        $class = $this->notificationClass;
        $notification = $class::findOne($id);
        if (!$notification) {
            throw new HttpException(404, "Unknown notification");
        }
        if ($notification->user_id != $this->user_id) {
            throw new HttpException(500, "Not your notification");
        }
        return $notification;
    }
    
    private function convertModelsToArray($models){
    		$results = [];
    		foreach ($models as $model) {
            // give user a chance to parse the date as needed
//             $date = date('Y-m-d H:i:s');
            /** @var Notification $model */
            $results[] = [
                'id' => $model->id,
                'type' => $model->type,
                'title' => $model->getTitle(),
                'body' => $model->getBody(),
            		'footer' => $model->getFooter(),
                'url' => Url::to(['notifications/rnr', 'id' => $model->id]),
                'key' => $model->key,
           	 	'key_id' => $model->key_id,
                'flashed' => $model->flashed,
            		'read' => $model->read,
                'date' => $model->created_at,
            ];
        }
        
        return $results;
    }
}
