<?php

class AdminController extends Controller
{
	public $defaultAction = 'admin';
	public $layout = '//layouts/column2';
	
	private $_model;

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
			['allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions' => ['admin', 'delete', 'create', 'update', 'view'],
				'users' => UserModule::getAdmins(),
			],
			['deny',  // deny all users
				'users' => ['*'],
			],
		];
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User'])) {
            $model->attributes = $_GET['User'];
        }

        $this->render('index', [
            'model' => $model,
        ]);
	}

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		$model = $this->loadModel();
		$this->render('view', [
			'model' => $model,
		]);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new User;
		$profile = new Profile;
		$this->performAjaxValidation([$model, $profile]);

		if (isset($_POST['User'])) {
			$model->attributes = $_POST['User'];
			$model->activkey = Yii::app()->controller->module->encrypting(microtime() . $model->password);
			$profile->attributes = $_POST['Profile'];
			$profile->user_id = 0;
			if ($model->validate() && $profile->validate()) {
				$model->password = Yii::app()->controller->module->encrypting($model->password);
				if ($model->save()) {
					$profile->user_id = $model->id;
					$profile->save();
					Yii::app()->user->setFlash('success', UserModule::t("Changes is saved."));
					$this->redirect(['view', 'id' => $model->id]);
				}
			} else {
				$profile->validate();
			}
			Yii::app()->user->setFlash('error', UserModule::t('There was an error saving changes'));
		}

		$this->render('create', [
			'model' => $model,
			'profile' => $profile,
		]);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
		$model = $this->loadModel();
		$profile = $model->profile;
		$this->performAjaxValidation([$model, $profile]);
		if (isset($_POST['User'])) {
			$model->attributes = $_POST['User'];
			$profile->attributes = $_POST['Profile'];
			
			if ($model->validate() && $profile->validate()) {
				$old_password = User::model()->notsafe()->findByPk($model->id);
				if ($old_password->password != $model->password) {
					$model->password = Yii::app()->controller->module->encrypting($model->password);
					$model->activkey = Yii::app()->controller->module->encrypting(microtime() . $model->password);
				}
				$model->save();
				$profile->save();
				Yii::app()->user->setFlash('success', UserModule::t("Changes is saved."));
				$this->redirect(['view', 'id' => $model->id]);
			} else {
				Yii::app()->user->setFlash('error', UserModule::t('There was an error saving changes'));
				$profile->validate();
			}
		}

		$this->render('update', [
			'model' => $model,
			'profile' => $profile,
		]);
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionDelete($id)
	{
		if (Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			try {
				$model = $this->loadModel();
				$profile = Profile::model()->findByPk($model->id);
				
				// Make sure profile exists
				if ($profile) {
					$profile->delete();
				}

				$model->delete();

			    if (!isset($_GET['ajax'])) {
					Yii::app()->user->setFlash('success', Yii::t('default', 'La operación se realizó con éxito'));
			    } else {
					echo $this->showFlashMessage('success', Yii::t('default', 'La operación se realizó con éxito'));
			    }
			} catch(CDbException $e) {
			    if (!isset($_GET['ajax'])) {
					Yii::app()->user->setFlash('error', Yii::t('default', 'Se ha producido un error al realizar la operación'));
			    } else {
					echo $this->showFlashMessage('error', Yii::t('default', 'Se ha producido un error al realizar la operación'));
 	
			    }
			}

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_GET['ajax'])) {
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['index']);
			}
		} else {
			throw new CHttpException(400, Yii::t('default', 'Request no válido.'));
		}
	}

	/**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($validate)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($validate);
            Yii::app()->end();
        }
    }	
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if ($this->_model === null) {
			if (isset($_GET['id'])) {
				$this->_model = User::model()->notsafe()->findbyPk($_GET['id']);
			}

			if ($this->_model === null) {
				throw new CHttpException(404, 'The requested page does not exist.');
			}
		}

		return $this->_model;
	}
	
}
