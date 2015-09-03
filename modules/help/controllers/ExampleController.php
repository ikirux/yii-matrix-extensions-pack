<?php

class ExampleController extends Controller
{
	public function behaviors()
	{
		return [
            'control-access' => [
                'class' => 'matrixAssets.behaviors.ControlAccessBehavior',
            ],		
		];
	}
		
	public function actionIndex()
	{
		$this->render('index');
	}
}