<?php

class ProfileController extends Controller
{
	public $defaultAction = 'profile';
	public $layout = '//layouts/column2';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	public function behaviors()
	{
		return [
            'control-access' => [
                'class' => 'matrixAssets.behaviors.ControlAccessBehavior',
            ],		
		];
	}
		
	/**
	 * Shows a particular model.
	 */
	public function actionProfile()
	{
		$model = $this->loadUser();
	    $this->render('profile', [
	    	'model' => $model,
			'profile' => $model->profile,
	    ]);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionEdit()
	{
		$model = $this->loadUser();
		$profile = $model->profile;
		
		// ajax validator
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'profile-form') {
			echo UActiveForm::validate([$model, $profile]);
			Yii::app()->end();
		}
		
		if (isset($_POST['User'])) {
			$model->attributes = $_POST['User'];
			$profile->attributes = $_POST['Profile'];
			
			if ($model->validate() && $profile->validate()) {
				$model->save();
				$profile->save();
				Yii::app()->user->setFlash('success', UserModule::t("Changes is saved."));
				$this->redirect(['/user/profile']);
			} else {
				$profile->validate();
				Yii::app()->user->setFlash('error', UserModule::t('There was an error saving changes'));
			}
		}

		$this->render('edit', [
			'model' => $model,
			'profile' => $profile,
		]);
	}
	
	/**
	 * Change password
	 */
	public function actionChangepassword() {
		$model = new UserChangePassword;

		if (Yii::app()->user->id) {
			
			// ajax validator
			if (isset($_POST['ajax']) && $_POST['ajax'] === 'changepassword-form') {
				echo UActiveForm::validate($model);
				Yii::app()->end();
			}
			
			if (isset($_POST['UserChangePassword'])) {
				$model->attributes = $_POST['UserChangePassword'];
				if ($model->validate()) {
					$new_password = User::model()->notsafe()->findbyPk(Yii::app()->user->id);
					$new_password->password = UserModule::encrypting($model->password);
					$new_password->activkey = UserModule::encrypting(microtime() . $model->password);
					if ($new_password->save()) {
						Yii::app()->user->setFlash('success', UserModule::t("New password is saved."));
						$this->redirect(["profile"]);						
					}
				}

				Yii::app()->user->setFlash('error', UserModule::t('There was an error saving changes'));
			}

			$this->render('changepassword', ['model' => $model]);
	    }
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadUser()
	{
		if ($this->_model === null) {
			if (Yii::app()->user->id) {
				$this->_model = Yii::app()->controller->module->user();
			}

			if ($this->_model === null) {
				$this->redirect(Yii::app()->controller->module->loginUrl);
			}
		}
		
		return $this->_model;
	}
}