<?php

// namespace frontend\modules\unsplash\controllers;
namespace cbtech\notification_system\controllers;

use yii\web\Controller;
use Crew\Unsplash\Photo;

/**
 * Ajax controller for the `unsplash` module
 */
class NotificationsController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionSearch()
    {
    	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
    	$search = \Yii::$app->request->get('search');
    	if($search == NULL){
    		$search = "colors";
    	}
        $page = \Yii::$app->request->get('page');;
        if($page == NULL){
        	$page = 1;
        }
        $per_page = \Yii::$app->request->get('per_page');;
        if($per_page == NULL){
        	$per_page = 16;
        }
        $orientation = \Yii::$app->request->get('orientation');
        if($orientation == NULL){
        	$orientation = 'landscape';
        }
        
        $pageResult = \Crew\Unsplash\Search::photos($search, $page, $per_page, $orientation);
// 		\Yii::error(print_r(\Yii::$app->request->get('search')));
		$utm_source = \Yii::$app->modules["unsplash"]->params['utmSource'];

        $html = $this->renderPartial('/_images',[
        		'pageResult'=>$pageResult,
        		'search'=>$search,
				'page'=>$page,
				'per_page'=>$per_page,
				'orientation'=>$orientation,
        		'utm_source'=>$utm_source
        ]);
		
		return ["returnCode"=>0,"returnCodeDescription"=>"Success","data"=>["html"=>$html]];
//         return $this->render('index');
    }
    
    public function actionGetPhotoUrls($id){
    	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	
    	$photo = Photo::find($id);
    	$downloadUrl = $photo->download();
//     	$thumbnailUrl = $photo->urls["thumb"];
    	
//     	\Yii::error(print_r($photo,true));
    	
    	return ["returnCode"=>0,"returnCodeDescription"=>"Success","data"=>["urls"=>$photo->urls,"downloadUrl"=>$downloadUrl]];
    }
}
