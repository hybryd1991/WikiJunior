<?php

class RatesController extends Controller
{
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated users to access all actions
				'actions'=>array('add','getRate', 'index'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			)
		);
	}
	//adding vote to comment and recieving rate
	public function actionAdd()
	{
		if(Yii::app()->request->isPostRequest){
			$rate = new Rates;
			
			$rate->comment_id = (int) Yii::app()->request->getParam('comment_id');
			$rate->rate = (int) Yii::app()->request->getParam('rate');
			
			if($rate->save())
				echo $rate->getRate($rate->comment_id);
			else
				echo 'error';
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