<?php
/**
 * EMultiSelect class file.
 *
 * PHP Version 5.1
 * 
 * @category Vencidi
 * @package  Widget
 * @author   Loren <wiseloren@yiiframework.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT
 * @link     http://www.vencidi.com/ Vencidi
 * @since    3.0
 */
Yii::import('zii.widgets.jui.CJuiWidget');
/**
 * EMultiSelect Creates Multiple Select Boxes
 *
 * @category Vencidi
 * @package  Widget
 * @author   Loren <wiseloren@yiiframework.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT
 * @version  Release: 1.0
 * @link     http://www.vencidi.com/ Vencidi
 * @since    3.0
 */
class EMultiSelect extends CJuiWidget
{
    public $sortable = true;
    public $searchable = true;
    public $height = false;
    public $width = false;
    public $dividerLocation = 0.6;

    /**
     * Initializes everything
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->registerScripts();
    }

    /**
     * Registers the JS and CSS Files
     *
     * @return void
     */
    protected function registerScripts()
    {
        parent::registerCoreScripts();
        $basePath = dirname(__FILE__ ) . DIRECTORY_SEPARATOR . 'assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($basePath);

        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($baseUrl . DIRECTORY_SEPARATOR . 'ui.multiselect.css');

        $this->scriptUrl=$baseUrl;
        $this->registerScriptFile($baseUrl . DIRECTORY_SEPARATOR . 'ui.multiselect.js');

        $params = [];
        $params[] = $this->sortable ? "sortable:true" : "sortable:false";
        $params[] = $this->searchable ? "searchable:true" : "searchable:false";
        $params[] = $this->height ? "height:" . $this->height : "height:false";
        $params[] = $this->width ? "width:" . $this->width : "width:false";                
        $params[] = "dividerLocation:" . $this->dividerLocation;

        $parameters = '{' . implode(',', $params) . '}';
        Yii::app()->clientScript->registerScript(
            'EMultiSelect',
            '$(".multiselect").multiselect(' . $parameters . ');',
            CClientScript::POS_READY
        );
    }
}