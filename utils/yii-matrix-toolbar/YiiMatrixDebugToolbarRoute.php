<?php
/**
 * YiiMatrixDebugToolbarRouter class file.
 * YiiDebugToolbarRouter++ :)
 *
 * @author Carlos Pinto <ikirux@gmail.com>
 * @package yii-matrix-extensions-pack
 */
Yii::import('ven.malyshev.yii-debug-toolbar.YiiDebugToolbarRoute');
class YiiMatrixDebugToolbarRoute extends YiiDebugToolbarRoute
{
    public function init()
    {
        Yii::app()->setImport([
        	'matrixAssets.utils.yii-matrix-toolbar.panels.*'
        ]);

        $this->setPanels([
            'YiiDebugToolbarPanelHelpCodeGenerator' => [],
        ]);
       
        parent::init();
    }
}