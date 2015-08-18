<?php $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', [
    'id' => 'profileField-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
    'htmlOptions' => ['enctype' => 'multipart/form-data'],
]); ?>

    <p class="help-block"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

    <?= $form->errorSummary($model); ?>

    <?= $form->textFieldControlGroup($model, 'varname', [
	    	'maxlength' => 50,
	    	'readonly' => isset($model->id) ? true : false,
	    	'help' => UserModule::t("Allowed lowercase letters and digits."),
    ]); ?>
    
    <?= $form->textFieldControlGroup($model, 'title', [
	    	'maxlength' => 255,
	    	'help' => UserModule::t('Field name on the language of "sourceLanguage".'),
    ]); ?>

	<?= $form->dropDownListControlGroup($model, 'field_type', ProfileField::itemAlias('field_type'), [
			'id' => 'field_type',
			'disabled' => isset($model->id) ? true : false,
			'help' => UserModule::t('Field type column in the database.'),
	]); ?> 

    <?= $form->textFieldControlGroup($model, 'field_size', [
	    	'maxlength' => 10,
	    	'help' => UserModule::t('Field size column in the database.'),
    ]); ?>

    <?= $form->textFieldControlGroup($model, 'field_size_min', [
	    	'maxlength' => 10,
	    	'help' => UserModule::t('The minimum value of the field (form validator).'),
    ]); ?>

	<?= $form->dropDownListControlGroup($model, 'required', ProfileField::itemAlias('required'), [
			'help' => UserModule::t('Required field (form validator).'),
	]); ?> 

    <?= $form->textFieldControlGroup($model, 'match', [
	    	'maxlength' => 255,
	    	'help' => UserModule::t("Regular expression (example: '/^[A-Za-z0-9\s,]+$/u')."),
    ]); ?>

    <?= $form->textFieldControlGroup($model, 'range', [
	    	'maxlength' => 5000,
	    	'help' => UserModule::t('Predefined values (example: 1;2;3;4;5 or 1==One;2==Two;3==Three;4==Four;5==Five).'),
    ]); ?>

    <?= $form->textFieldControlGroup($model, 'error_message', [
	    	'maxlength' => 255,
	    	'help' => UserModule::t('Error message when you validate the form.'),
    ]); ?>

    <?= $form->textFieldControlGroup($model, 'other_validator', [
	    	'maxlength' => 255,
	    	'help' => UserModule::t('JSON string (example: {example}).', ['{example}' => CJavaScript::jsonEncode(['file' => ['types' => 'jpg, gif, png']])]),
    ]); ?>

    <?= $form->textFieldControlGroup($model, 'default', [
	    	'maxlength' => 255,
	    	'readonly' => isset($model->id) ? true : false,
	    	'help' => UserModule::t('The value of the default field (database).'),
    ]); ?>

	<?php 
		list($widgetsList) = ProfileFieldController::getWidgets($model->field_type);
		echo $form->dropDownListControlGroup($model, 'widget', $widgetsList, [
			'id' => 'widgetlist',
			'help' => UserModule::t('Widget name.'),
		]);	
	?>

    <?= $form->textFieldControlGroup($model, 'widgetparams', [
	    	'id' => 'widgetparams',
	    	'maxlength' => 5000,
	    	'help' => UserModule::t('JSON string (example: {example}).', ['{example}' => CJavaScript::jsonEncode(['param1' => ['val1', 'val2'], 'param2' => ['k1' => 'v1', 'k2' => 'v2']])]),
    ]); ?>

    <?= $form->textFieldControlGroup($model, 'position', [
	    	'maxlength' => 5,
	    	'help' => UserModule::t('Display order of fields.'),
    ]); ?>

	<?= $form->dropDownListControlGroup($model, 'visible', ProfileField::itemAlias('visible')); ?> 

    <?= BsHtml::submitButton($model->isNewRecord ? UserModule::t('Create') : UserModule::t('Save'), [
    	'color' => BsHtml::BUTTON_COLOR_PRIMARY]
    ); ?>

<?php $this->endWidget(); ?>
<div id="dialog-form" title="<?php echo UserModule::t('Widget parametrs'); ?>">
	<form>
	<fieldset>
		<label for="name">Name</label>
		<input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" />
		<label for="value">Value</label>
		<input type="text" name="value" id="value" value="" class="text ui-widget-content ui-corner-all" />
	</fieldset>
	</form>
</div>
