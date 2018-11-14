<?php

// namespace frontend\modules\unsplash;
namespace cbtech\notification_system;

/**
 * unsplash module definition class
 */
class NotificationSystemModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */

	public $controllerNamespace = 'cbtech\notification_system\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
//         \Crew\Unsplash\HttpClient::init([
// 			'applicationId'	=> $this->params['applicationId'], //Access Key in UnSplash App info.
// 			'utmSource' => $this->params['utmSource'],
// 		]);
    }
}
