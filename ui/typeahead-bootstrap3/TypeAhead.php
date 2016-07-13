<?php
/**
 * TypeAhead class file.
 * @author Carlos Pinto <ikirux@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 *
 */

class TypeAhead extends CInputWidget
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
	 * @var string[] the JavaScript models related.
	 */
	public $related = [];
        
	/**
	 * @var string[] the JavaScript event handlers.
	 */
	public $events = [];
    
	/**
	 * @var string[] the JavaScript event handlers.
	 */
	public $customEvents = [];
    
    /**
    * @var string
    */
    public $action = 'getJson';
    
	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		if (!isset($this->options['minLength'])) {
			$this->options['minLength'] = 2;
		}

		if (!isset($this->options['limit'])) {
			$this->options['limit'] = 5;
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
    	
        $classErrorDiv = ($error = $this->model->getError($this->attribute)) ? 'has-error' : '' ;
        echo CHtml::tag('div', ['class' => 'form-group ' . $classErrorDiv], false, false);
        echo CHtml::activeLabel($this->model, $this->attribute, ['class' => 'control-label', 'required' => $required]);
        echo CHtml::tag('div', [], false, false);
        echo CHtml::activeTextField($this->model, $this->attribute, ['class' => 'form-control', 'placeholder' => $this->model->getAttributeLabel($this->attribute)]);
        if (!empty($classErrorDiv)) { echo CHtml::tag('p', ['class' => 'help-block'], $error); }
        echo CHtml::closeTag('div');
        echo CHtml::closeTag('div');
        $this->registerClientScript($id);
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
                YII_DEBUG ? 'js/bloodhound.js' : 'js/bloodhound.min.js',
                YII_DEBUG ? 'js/typeahead.bundle.js' : 'js/typeahead.bundle.min.js',
                YII_DEBUG ? 'js/typeahead.jquery.js' : 'js/typeahead.jquery.min.js',
            ],
            'css' => [
                '/css/typeahead.css',
            ],
            'depends' => [
                'jquery',
            ],
        ], $this->package);

        $clientScript = Yii::app()->getClientScript();
		$options = !empty($this->options) ? CJavaScript::encode($this->options) : '';

        $url = explode('/', Yii::app()->request->url);
        array_pop($url);
        $urlForAction = implode('/', $url);
       
        $relatedModel = key($this->related);
        $relatedAttribute = current($this->related);
        $route = $urlForAction . '/' . $this->action . '?model=' . $relatedModel . '&attribute=' . $relatedAttribute . '&query=%QUERY&limit=' . $this->options['limit'];
        
        $eventsCustomScript = [];
        foreach($this->customEvents as $event => $expression) {
            $eventsCustomScript[] = ".on('typeahead:{$event}',{$expression})";
        }
        $eventsCustomScript = implode("\n", $eventsCustomScript);

        $eventsScript = [];
        foreach($this->events as $event => $expression) {
            $eventsScript[] = ".on('$event',{$expression})";
        }
        $eventsScript = implode("\n", $eventsScript);
        
        $script = "var contentMotor = new Bloodhound({
        	datumTokenizer: Bloodhound.tokenizers.obj.whitespace('" . $relatedAttribute . "'),
        	queryTokenizer: Bloodhound.tokenizers.whitespace,
        	limit: " . $this->options['limit'] . ",
        	remote: {url: '" . $route . "'}
        });		
        		
        contentMotor.initialize();
        $('#" . $id . "').typeahead({ highlight: true, minLength: " . $this->options['minLength'] . " }, {
        	name: 'json-search',
        	displayKey: '" . $relatedAttribute . "',
        	limit: " . $this->options['limit'] . ",
        	source: contentMotor.ttAdapter()
        })
        $eventsCustomScript
        $eventsScript";
        
        $clientScript
            ->addPackage(self::PACKAGE_ID, $this->package)
            ->registerPackage(self::PACKAGE_ID)
            ->registerScript(
                __CLASS__ . '#' . $this->getId(),
                $script, CClientScript::POS_END);           
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
