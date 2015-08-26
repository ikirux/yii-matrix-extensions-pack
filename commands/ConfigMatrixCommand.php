<?php

/**
 * Realiza la configuracion inicial de una aplicacion que utiliza el boilerplate yii-matrix
 */
class ConfigMatrixCommand extends CConsoleCommand
{
	public function actionIndex()
	{
		$processUser = @posix_getpwuid(posix_geteuid());
		if ($processUser['name'] != "root") {
		   exit("You must run this script like root user \n");
		}
    }
}