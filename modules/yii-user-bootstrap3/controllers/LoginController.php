<?php

class LoginController extends Controller
{
	public $defaultAction = 'login';

	public function behaviors()
	{
		return [
            'control-access' => [
                'class' => 'matrixAssets.behaviors.ControlAccessBehavior',
            ],		
		];
	}
	
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if (Yii::app()->user->isGuest) {
			$model = new UserLogin;
			// collect user input data
			if (isset($_POST['UserLogin'])) {
				$model->attributes = $_POST['UserLogin'];
				// validate user input and redirect to previous page if valid
				if ($model->validate()) {
					$this->lastViset();
					$this->redirect(Yii::app()->controller->module->returnUrl);
				}
			}
			// display the login form
			$this->render('/user/login', ['model' => $model]);
		} else {
			$this->redirect(Yii::app()->controller->module->returnUrl);
		}
	}
	
	private function lastViset() {
		$lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);
		$lastVisit->lastvisit_at = date('Y-m-d H:i:s');
		$lastVisit->save();
	}
}
