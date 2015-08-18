<?php

class UWjuiAutoComplete {
	
	public $params = [
		'ui-theme' => 'base',
		'modelName' => '',
		'optionName' => '',
		'emptyFieldLabel' => 'Not found',
		'emptyFieldValue' => 0,
		'relationName' => '',
		'minLength' => '',
	];
	
	/**
	 * Widget initialization
	 * @return array
	 */
	public function init() {
		return [
			'name' => __CLASS__,
			'label' => UserModule::t('jQueryUI autocomplete', [], __CLASS__),
			'fieldType' => ['INTEGER'],
			'params' => $this->params,
			'paramsLabels' => [
				'modelName' => UserModule::t('Model Name', [], __CLASS__),
				'optionName' => UserModule::t('Lable field name', [], __CLASS__),
				'emptyFieldLabel' => UserModule::t('Empty item name', [], __CLASS__),
				'emptyFieldValue' => UserModule::t('Empty item value', [], __CLASS__),
				'relationName' => UserModule::t('Profile model relation name', [], __CLASS__),
				'minLength' => UserModule::t('minimal start research length', [], __CLASS__),
			],
		];
	}
	
	/**
	 * @param $value
	 * @param $model
	 * @param $field_varname
	 * @return string
	 */
	public function setAttributes($value, $model, $field_varname) {
		return $value;
	}
	
	/**
	 * @param $model - profile model
	 * @param $field - profile fields model item
	 * @return string
	 */
	public function viewAttribute($model, $field) {
		$relation = $model->relations();
		if ($this->params['relationName'] && isset($relation[$this->params['relationName']])) {
			$m = $model->__get($this->params['relationName']);
		} else {
			$m = CActiveRecord::model($this->params['modelName'])->findByPk($model->getAttribute($field->varname));
		}
		
		if ($m) {
			return (($this->params['optionName']) ? $m->getAttribute($this->params['optionName']) : $m->id);
		} else {
			return $this->params['emptyFieldLabel'];
		}
	}
	
	/**
	 * @param $model - profile model
	 * @param $field - profile fields model item
	 * @param $params - htmlOptions
	 * @return string
	 */
	public function editAttribute($model, $field, $htmlOptions = []) {
		$list = [];
		if (isset($this->params['emptyFieldValue'])) {
			$list[] = ['id' => $this->params['emptyFieldValue'], 'label' => $this->params['emptyFieldLabel']];
		}	

		$models = CActiveRecord::model($this->params['modelName'])->findAll();

		foreach ($models as $m) {
			$list[] = (($this->params['optionName']) ? ['label' => $m->getAttribute($this->params['optionName']), 'id' => $m->id] : ['label' => $m->id, 'id' => $m->id]);
		}
		
		if (!isset($htmlOptions['id'])) {
			$htmlOptions['id'] = $field->varname;
		}

		$id = $htmlOptions['id'];
		
		$relation = $model->relations();
		if ($this->params['relationName'] && isset($relation[$this->params['relationName']])) {
			$m = $model->__get($this->params['relationName']);
		} else {
			$m = CActiveRecord::model($this->params['modelName'])->findByPk($model->getAttribute($field->varname));
		}
		
		if ($m) {
			$default_value = (($this->params['optionName']) ? $m->getAttribute($this->params['optionName']) : $m->id);
		} else {
			$default_value = '';
		}
		
		$htmlOptions['value'] = $default_value;
		$options['source'] = $list;
		$options['minLength'] = $this->params['minLength'];
		$options['showAnim'] = 'fold';
		$options['select'] = "js:function(event, ui) { $('#" . get_class($model) . "_" . $field->varname . "').val(ui.item.id);}";
		$options = CJavaScript::encode($options);
		//$basePath=Yii::getPathOfAlias('application.views.asset');
		$basePath = Yii::getPathOfAlias('application.modules.user.views.asset');
		$baseUrl = Yii::app()->getAssetManager()->publish($basePath);
		$cs = Yii::app()->getClientScript();
		$cs->registerCssFile($baseUrl . '/css/' . $this->params['ui-theme'] . '/jquery-ui.css');
		$cs->registerScriptFile($baseUrl . '/js/jquery-ui.min.js');
		$js = "jQuery('#{$id}').autocomplete({$options});";
		$cs->registerScript('Autocomplete' . '#' . $id, $js);
		
		return CHtml::activeTextField($model, $field->varname, $htmlOptions) . CHtml::activehiddenField($model, $field->varname);
	}
} 