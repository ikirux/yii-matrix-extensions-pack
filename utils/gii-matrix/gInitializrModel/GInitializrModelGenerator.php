<?php

/**
 * GInitializrModelGenerator class file.
 *
 * @author Carlos Pinto <ikirux@gmail.com>
 * @link http://developen.cl
 * @copyright Copyright &copy; 2014 Carlos Pinto
 * @license http://giix.org/license/ New BSD License
 */

/**
 * GiixModelGenerator is the controller for giix model generator.
 *
 * @author Rodrigo Coelho <rodrigo@giix.org>
 */
class GInitializrModelGenerator extends CCodeGenerator {

	public $codeModel = 'application.vendor.ikirux.gii-matrix.gInitializrModel.GInitializrModelCode';

	/**
	 * Returns the table names in an array.
	 * The array is used to build the autocomplete field.
	 * An '*' is appended to the end of the list to allow the generation
	 * of models for all tables.
	 * @return array The names of all tables in the schema, plus an '*'.
	 */
	protected function getTables() {
		$tables = Yii::app()->db->schema->tableNames;
		$tables[] = '*';
		return $tables;
	}

}