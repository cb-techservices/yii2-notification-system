<?php 

// namespace frontend\modules\unsplash;
namespace cbtech\notification_system\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use Crew\Unsplash\HttpClient;
use cbtech\unsplash\assets\AppAsset;

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
        if ($this->message === null) {
            $this->message = 'Hello World';
        }
        
        if($this->button_text === null){
        	$this->button_text = "Choose photo from Unsplash";
        }
        
    	if($this->button_class === null){
        	$this->button_class = "btn btn-success";
        }
        
        if($this->button_style === null){
        	$this->button_style = "";
        }
        
        
        HttpClient::$utmSource = "LeP Photo Extension";
        \Crew\Unsplash\HttpClient::init([
			'applicationId'	=> \Yii::$app->modules["unsplash"]['params']['applicationId'],
			'utmSource' => \Yii::$app->modules["unsplash"]['params']['utmSource'],
		]);
        $connection = HttpClient::$connection;
        
        //Load AppAssets
        AppAsset::register($this->view);
    }

    public function run()
    {
//     	\Yii::error(print_r($this->search()));
		$result = $this->search($this->search,$this->page, $this->per_page, $this->orientation);
//         return Html::encode($this->message);
		return $this->render('picker',[
				'pageResult'=>$result,
				'search'=>$this->search,
				'page'=>$this->page,
				'per_page'=>$this->per_page,
				'orientation'=>$this->orientation,
				'button_text'=>$this->button_text,
				'button_class'=>$this->button_class,
				'button_style'=>$this->button_style,
		]);
    }
    
    public function search($search = 'colors', $page = 1, $per_page = 16, $orientation = 'landscape'){
		$pageResult = \Crew\Unsplash\Search::photos($search, $page, $per_page, $orientation);
// 		\Yii::error(print_r($pageResult,true));
		return $pageResult;
    }
}