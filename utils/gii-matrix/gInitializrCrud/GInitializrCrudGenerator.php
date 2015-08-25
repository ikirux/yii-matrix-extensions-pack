<?php
/**
 * BootstrapGenerator class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package bootstrap.gii
 */

Yii::import('gii.generators.crud.CrudGenerator');

class GInitializrCrudGenerator extends CrudGenerator
{
    public $codeModel = 'application.vendor.ikirux.yii-matrix-extensions-pack.utils.gii-matrix.gInitializrCrud.GInitializrCrudCode';
}