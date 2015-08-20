<?php
/**
 * TypeAhead class file.
 * @author Gilles Grandguillaume <gilles.grandguillaume@segic.usach.cl>
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @since 0.0.1
 */

class TypeAhead extends CInputWidget
{
	public $minLength = "2";
	
	public $related = [];
	    
    /**
     * @var integer limit element per search
     */
    public $limit = 5;

    public $events = [];

    public $customEvents = [];

    public $action = 'getJson';

    /**
     * Runs the widget.
     */
    public function run()
    {
    	list($name, $id) = $this->resolveNameID();
    	
    	$required = false;
    	
    	foreach ($this->model->getValidators($this->attribute) as $validator)
    		if ($validator instanceof CRequiredValidator && in_array($this->attribute, $validator->attributes)) {
    			$required = true;
    			break;
    		}
    	
        $classErrorDiv = ($error = $this->model->getError($this->attribute)) ? 'has-error' : '' ;
        echo CHtml::tag('div', ['class' => 'form-group ' . $classErrorDiv], false, false);
        echo CHtml::activeLabel($this->model, $this->attribute, ['class' => 'control-label', 'required' => $required]);
        echo CHtml::tag('div', [], false, false);
        echo CHtml::activeTextField($this->model, $this->attribute, ['class' => 'form-control', 'placeholder' => $this->model->getAttributeLabel($this->attribute)]);
        if (!empty($classErrorDiv)) { echo CHtml::tag('p', ['class' => 'help-block'], $error); }
        echo CHtml::closeTag('div');
        echo CHtml::closeTag('div');
                
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        
        $bloodhound = YII_DEBUG ? 'bloodhound.js' : 'bloodhound.min.js';
        $typeaheadBundle =  YII_DEBUG ? 'typeahead.bundle.js' : 'typeahead.bundle.min.js';
        $typeaheadJquery =  YII_DEBUG ? 'typeahead.jquery.js' : 'typeahead.jquery.min.js';

        $assetsPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
        $assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, false);
        
        $cs->registerCssFile($assetsUrl . '/css/typeahead.css');
        
        $cs->registerScriptFile($assetsUrl . '/js/' . $bloodhound, CClientScript::POS_HEAD);
        $cs->registerScriptFile($assetsUrl . '/js/' . $typeaheadBundle, CClientScript::POS_HEAD);
        $cs->registerScriptFile($assetsUrl . '/js/' . $typeaheadJquery, CClientScript::POS_HEAD);
                
        $url = explode('/', Yii::app()->request->url);
        array_pop($url);
        $urlForAction = implode('/', $url);
       
        $relatedModel = key($this->related);
        $relatedAttribute = current($this->related);
        $route = $urlForAction . '/' . $this->action . '?model=' . $relatedModel . '&attribute=' . $relatedAttribute . '&query=%QUERY&limit=' . $this->limit;

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
        	limit: " . $this->limit . ",
        	remote: {url: '" . $route . "'}
        });		
        		
        contentMotor.initialize();
        $('#" . $id . "').typeahead({ highlight: true, minLength: " . $this->minLength . " }, {
        	name: 'json-search',
        	displayKey: '" . $relatedAttribute . "',
        	limit: " . $this->limit . ",
        	source: contentMotor.ttAdapter()
        })
        $eventsCustomScript
        $eventsScript";
        
        $cs->registerScript("def-typeAhead" . $this->attribute, $script, CClientScript::POS_END);
    }

}
