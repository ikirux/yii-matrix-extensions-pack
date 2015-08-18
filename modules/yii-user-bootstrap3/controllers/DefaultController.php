<?php

class DefaultController extends Controller
{
	
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return CMap::mergeArray(parent::filters(), [
			'accessControl', // perform access control for CRUD operations
		]);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return [
			['allow',  // allow all users to perform 'index' and 'view' actions
				'actions' => ['index'],
				'users' => UserModule::getAdmins(),
			],
			['deny',  // deny all users
				'users' => ['*'],
			],
		];
	}	

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('User', [
			'criteria' => [
		        'condition' => 'status>' . User::STATUS_BANNED,
		    ],
			'pagination' => [
				'pageSize' => Yii::app()->controller->module->user_page_size,
			],
		]);

		$this->render('/user/index', [
			'dataProvider' => $dataProvider,
		]);
	}

}