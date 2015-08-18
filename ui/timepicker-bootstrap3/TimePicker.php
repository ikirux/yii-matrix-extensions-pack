<?php
/**
 * TimePicker class file.
 * https://github.com/rendom/bootstrap-3-timepicker
 * @author Carlos Pinto
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @since 0.0.1
 */

class TimePicker extends CInputWidget
{
    /**
     * Assets package ID.
     */
    const PACKAGE_ID = 'time-picker';

    /**
     * @var array {@link http://jdewit.github.io/bootstrap-timepicker/}.
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
        
        $required = false;
        
        foreach ($this->model->getValidators($this->attribute) as $validator) {
            if ($validator instanceof CRequiredValidator && in_array($this->attribute, $validator->attributes)) {
                $required = true;
                break;
            }            
        }
        
        $classErrorDiv = ($error = $this->model->getError($this->attribute)) ? 'has-error' : '' ;
        echo CHtml::tag('div', ['class' => 'form-group bootstrap-timepicker' . $classErrorDiv], false, false);
        echo CHtml::activeLabel($this->model, $this->attribute, ['class' => 'control-label', 'required' => $required]);
        echo CHtml::tag('div', [], false, false);
        echo CHtml::activeTextField($this->model, $this->attribute, ['class' => 'form-control', 'placeholder' => $this->model->getAttributeLabel($this->attribute)]);
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
        $this->package = array_merge(array(
            'baseUrl' => $this->getAssetsUrl(),
            'js' => [
                YII_DEBUG ? 'js/bootstrap-timepicker.js' : 'js/bootstrap-timepicker.min.js',
            ],
            'css' => [
                YII_DEBUG ? 'css/bootstrap-timepicker.css' : 'css/bootstrap-timepicker.min.css'
            ],
            'depends' => [
                'jquery',
            ],
        ), $this->package);

        $clientScript = Yii::app()->getClientScript();
        $options = CJavaScript::encode($this->options);

        $clientScript
            ->addPackage(self::PACKAGE_ID, $this->package)
            ->registerPackage(self::PACKAGE_ID)
            ->registerScript(
                $this->id,
                'jQuery(\'#' . $id . '\').timepicker(' . $options . ');',
                    CClientScript::POS_READY
                );
    }

    /**
    * Get the assets path.
    * @return string
    */
    public function getAssetsPath()
    {
        return dirname(__FILE__) . '/assets';
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