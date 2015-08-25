<?php

class GInitializrModuleGenerator extends CCodeGenerator
{
	public $codeModel = 'application.vendor.ikirux.yii-matrix-extensions-pack.utils.gii-matrix.gInitializrModule.GInitializrModuleCode';
    
    protected function afterAction($action)
    {
    	// Se deben generar las operaciones del modulo auth
    	if ($action->id == 'index') {
	    	if (isset($_POST['generate'])) {
	    		$this->createAuthCode();
	    	}
    	}
    }

    private function createAuthCode()
    {
    	//GInitializrCrudCode
		$model = $this->prepare();
		$model->attributes = $_POST;

		shell_exec(Yii::getPathOfAlias('application') . DIRECTORY_SEPARATOR . 'yiic managepermissions createModule --moduleName=' . $model->moduleID);
    }	
}