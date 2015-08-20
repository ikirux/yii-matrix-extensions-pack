<?php
/**
 * MultiSelect class file.
 * http://loudev.com/
 * @author Carlos Pinto
 * @version 0.0.1
 */

class MultiSelect extends CInputWidget
{
    /**
     * Assets package ID.
     */
    const PACKAGE_ID = 'multi-select';

    /**
     * @var array {@link http://loudev.com/}.
    */
    public $options = [];

    /**
    * @var array
    */
    public $package = [];

    /**
    * Init widget.
    */
    public function init()
    {
        parent::init();

        // Marcamos con * en caso de ser necesario        
        $required = false;
        foreach ($this->model->getValidators($this->attribute) as $validator) {
            if ($validator instanceof CRequiredValidator && in_array($this->attribute, $validator->attributes)) {
                $required = true;
                break;
            }            
        }
        
        $classErrorDiv = ($error = $this->model->getError($this->attribute)) ? 'has-error' : '' ;
        echo CHtml::tag('div', ['class' => 'form-group ' . $classErrorDiv], false, false);
        echo CHtml::activeLabel($this->model, $this->attribute, ['class' => 'control-label', 'required' => $required]);
        echo CHtml::tag('div', [], false, false);
        echo CHtml::activeDropDownList($this->model, $this->attribute, ['class' => 'form-control']);
        if (!empty($classErrorDiv)) { echo CHtml::tag('p', ['class' => 'help-block'], $error); }
        echo CHtml::closeTag('div');
        echo CHtml::closeTag('div');
        $this->registerClientScript();
    }

    /**
    * Register CSS and Script.
    */
    protected function registerClientScript()
    {
        list($name, $id) = $this->resolveNameID();

        // Prepare script package.
        $this->package = array_merge([
            'baseUrl' => $this->getAssetsUrl(),
            'js' => [
                YII_DEBUG ? 'js/jquery.multi-select.js' : 'js/jquery.multi-select.min.js',
            ],
            'css' => [
                YII_DEBUG ? 'css/multi-select.css' : 'css/multi-select.min.css'
            ],
            'depends' => [
                'jquery',
            ],
        ], $this->package);

        $clientScript = Yii::app()->getClientScript();
        $options = CJavaScript::encode($this->options);

        $clientScript
            ->addPackage(self::PACKAGE_ID, $this->package)
            ->registerPackage(self::PACKAGE_ID)
            ->registerScript(
                $this->id,
                'jQuery(\'#' . $id . '\').multiSelect(' . $options . ');',
                CClientScript::POS_READY
            );
    }

    /**
    * Get the assets path.
    * @return string
    */
    public function getAssetsPath()
    {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
    }

    /**
    * Publish assets and return url.
    * @return string
    */
    public function getAssetsUrl()
    {
        return Yii::app()->getAssetManager()->publish($this->getAssetsPath());
    }
}