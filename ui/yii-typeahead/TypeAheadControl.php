<?php
/**
 * TypeAheadControl widget class
 *
 * @author: Carlos Pinto <ikirux@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 *
 */
class TypeAheadControl extends CInputWidget
{
    /**
     * Assets package ID.
     */
    const PACKAGE_ID = 'yii-typeahead';

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
	 * @var boolean generate wrapper div.
	 */
	public $controlGroup = true;

	/**
	 * Initializes the widget.
	 */
	public function init()
	{
	}

	/**
	 * Runs the widget.
     *
     *   <div class="typeahead__container">
     *       <div class="typeahead__field">
     *
     *           <span class="typeahead__query">
     *               <input class="js-typeahead"
     *                   name="q"
     *                   type="search"
     *                   autocomplete="off">
     *           </span>
     *           <span class="typeahead__button">
     *               <button type="submit">
     *                   <span class="typeahead__search-icon"></span>
     *               </button>
     *           </span>
     *
     *       </div>
     *   </div>
     *
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

			if ($this->controlGroup) {
				echo CHtml::tag('div', ['class' => 'form-group ' . $classErrorDiv], false, false);
				echo CHtml::activeLabel($this->model, $this->attribute, ['class' => 'control-label', 'required' => $required]);				
			}

			echo CHtml::tag('div', [], false, false);
			echo CHtml::activeTextField($this->model, $this->attribute, [
				'class' => 'form-control', 
				'placeholder' => isset($this->htmlOptions['placeholder']) ? $this->htmlOptions['placeholder'] : $this->model->getAttributeLabel($this->attribute),
				'name' => $name,
				'id' => $id,
			]);
			if (!empty($classErrorDiv)) { echo CHtml::tag('p', ['class' => 'help-block'], $error); }
			echo CHtml::closeTag('div');

			if ($this->controlGroup) {
				echo CHtml::closeTag('div');
			}
				
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
                YII_DEBUG ? 'jquery.typeahead.js' : 'jquery.typeahead.min.js',
            ],
            'css' => [
                YII_DEBUG ? 'jquery.typeahead.css' : 'jquery.typeahead.min.css'
            ],
            'depends' => [
                'jquery',
            ],
        ], $this->package);

        $clientScript = Yii::app()->getClientScript();
		$options = !empty($this->options) ? CJavaScript::encode($this->options) : '';

        $clientScript
            ->addPackage(self::PACKAGE_ID, $this->package)
            ->registerPackage(self::PACKAGE_ID);         

		ob_start();
		echo "jQuery('#{$id}').typeahead({$options});";
		
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