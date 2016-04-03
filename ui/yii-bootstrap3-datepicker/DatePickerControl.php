<?php
/**
 * DatePickerControl widget class
 *
 * @author: Carlos Pinto <ikirux@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 *
 */
class DatePickerControl extends CInputWidget
{
    /**
     * Assets package ID.
     */
    const PACKAGE_ID = 'yii-datepicker-bootstrap3';

    /**
    * @var array
    */
    public $package = [];

	/**
	 * @var array the options for the Bootstrap JavaScript plugin.
	 */
	public $options = [];

	/**
	 * @var string[] the JavaScript event handlers.
	 */
	public $events = [];

	/**
	 * @var string the date picker type (text and embedded are supported).
	 */
	public $type = 'text';

	/**
	 * @var string[] triggers the specifics events after the date picker is load
	 */
	public $triggerEvents = [];

	/**
	 * @var string[] call the specified methods
	 * 
	 * format:
	 *
	 *  [
	 *		'name' => 'someName',
	 *		'arg1' => 'arg1',
	 *		'arg2' => 'arg2',
	 *  ]
	 */
	public $methods = [];

	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		if (!isset($this->options['language'])) {
			$this->options['language'] = Yii::app()->language;
		}

		if (!isset($this->options['format'])) {
			$this->options['format'] = 'dd/mm/yyyy';
		} 
		
		if (!isset($this->options['weekStart'])) {
			$this->options['weekStart'] = 0; // Sunday
		}
	}

	/**
	 * Runs the widget.
	 */
	public function run()
	{
		list($name, $id) = $this->resolveNameID();

		$required = false;
    	
    	foreach ($this->model->getValidators($this->attribute) as $validator) {
    		if ($validator instanceof CRequiredValidator && in_array($this->attribute, $validator->attributes)) {
    			$required = true;
    			break;
    		}
    	}

		if ($this->type == 'embedded') {
			$idEmbeddedContainer = $this->id . '_container';
			echo CHtml::tag('div', ['id' => $idEmbeddedContainer], false, false);
			echo CHtml::activeHiddenField($this->model, $this->attribute);
			$this->events['changeDate'] = 'js:function(e)  {
                dates = $("#' . $idEmbeddedContainer . '").datepicker(\'getFormattedDate\', \'' . $this->options['format'] . '\');
                $("#' . $id . '").val(dates);
            }';
   			echo CHtml::closeTag('div');
			$this->registerClientScript($idEmbeddedContainer);
		} else { // default 'text'
			$classErrorDiv = ($error = $this->model->getError($this->attribute)) ? 'has-error' : '' ;
			echo CHtml::tag('div', ['class' => 'form-group ' . $classErrorDiv], false, false);
			echo CHtml::activeLabel($this->model, $this->attribute, ['class' => 'control-label', 'required' => $required]);
			echo CHtml::tag('div', [], false, false);
			echo CHtml::activeTextField($this->model, $this->attribute, [
				'class' => 'form-control', 
				'placeholder' => isset($this->htmlOptions['placeholder']) ? $this->htmlOptions['placeholder'] : $this->model->getAttributeLabel($this->attribute),
				'name' => $name,
				'id' => $id,
			]);
			if (!empty($classErrorDiv)) { echo CHtml::tag('p', ['class' => 'help-block'], $error); }
			echo CHtml::closeTag('div');
			echo CHtml::closeTag('div');
			$this->registerClientScript($id);
		}
	}

	/**
	 * Registers required client script for bootstrap datepicker. It is not used through bootstrap->registerPlugin
	 * in order to attach events if any
	 */
	public function registerClientScript($id)
	{
        // Prepare script package.
        $this->package = array_merge([
            'baseUrl' => $this->getAssetsUrl(),
            'js' => [
                YII_DEBUG ? 'js/bootstrap-datepicker.js' : 'js/bootstrap-datepicker.min.js',
                $this->options['language'] != "en" ? 'js/locales/bootstrap-datepicker.' . $this->options['language'] . '.js' : '',
            ],
            'css' => [
                YII_DEBUG ? 'css/datepicker3.css' : 'css/datepicker3.min.css'
            ],
            'depends' => [
                'jquery',
                'jquery.ui',
            ],
        ], $this->package);

        $clientScript = Yii::app()->getClientScript();
		$options = !empty($this->options) ? CJavaScript::encode($this->options) : '';

        $clientScript
            ->addPackage(self::PACKAGE_ID, $this->package)
            ->registerPackage(self::PACKAGE_ID);         

		ob_start();
		echo "jQuery('#{$id}').datepicker({$options})";
		foreach ($this->methods as $method) {
			$argument = '';
			if (isset($method['name'])) {
				$argument = "'{$method['name']}'";

				// the name is required
				if (isset($method['arg1'])) {
					$argument .= ", {$method['arg1']}";
				}

				if (isset($method['arg2'])) {
					$argument .= ", {$method['arg2']}";
				}				
			}	
			
			if (!empty($argument)) {
				echo ".datepicker($argument)";	
			}
		}

		foreach ($this->events as $event => $handler) {
			echo ".on('{$event}', " . CJavaScript::encode($handler) . ")";
		}

		echo ';';

		foreach($this->triggerEvents as $event) {
			echo "jQuery('#{$id}').trigger('{$event}');";	
		}
		
		$clientScript->registerScript(__CLASS__ . '#' . $this->getId(), ob_get_clean());                   
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