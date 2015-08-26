<?php

class RatesController extends Controller
{
	//adding vote to comment and recieving rate
	public function actionAdd()
	{
		if(Yii::app()->request->isPostRequest){
			$comment = Yii::app()->request->getParam('id');
			$vote = Yii::app()->request->getParam('vote');
			$user = Yii::app()->user->id;

			$rate = new Rates;
			if(!$rate->addRate($comment, $user, $vote)){
				echo 'error';
			}else{
				echo $this->actionGetRate($comment);
			}
		}else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	//get comment rate
	public function actionGetRate()
	{
		if(Yii::app()->request->isPostRequest){
			$comment = Yii::app()->request->getParam('id');
			$rate = new Rates;

			return $rate->getRate($comment, Yii::app()->user->id);
		}else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function actionIndex()
	{
		$this->render('index');
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}