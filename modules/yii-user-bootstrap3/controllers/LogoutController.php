<?php

class LogoutController extends Controller
{
	public $defaultAction = 'logout';
	
	public function behaviors()
	{
		return [
            'control-access' => [
                'class' => 'matrixAssets.behaviors.ControlAccessBehavior',
            ],		
		];
	}
		
	/**
	 * Logout the current user and redirect to returnLogoutUrl.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->controller->module->returnLogoutUrl);
	}

}