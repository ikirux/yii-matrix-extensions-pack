<?php $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', [
    'id' => 'user-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
    'htmlOptions' => ['enctype' => 'multipart/form-data'],
]); ?>

    <p class="help-block"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

    <?= $form->errorSummary($model); ?>

    <?= $form->textFieldControlGroup($model, 'username', ['maxlength' => 20]); ?>
    <?= $form->passwordFieldControlGroup($model, 'password', ['maxlength' => 128]); ?>    
    <?= $form->emailFieldControlGroup($model, 'email', ['maxlength' => 128]); ?> 
    <?= $form->dropDownListControlGroup($model, 'superuser', User::itemAlias('AdminStatus')); ?> 
	<?= $form->dropDownListControlGroup($model, 'status', User::itemAlias('UserStatus')); ?> 

	<?php 
		$profileFields = Profile::getFields();
		if ($profileFields):
			foreach($profileFields as $field):
				if ($widgetEdit = $field->widgetEdit($profile)) {
					echo $form->labelEx($profile,$field->varname);
					echo $widgetEdit;
					echo $form->error($profile,$field->varname);
				} elseif ($field->range) {
					echo $form->dropDownListControlGroup($profile, $field->varname, Profile::range($field->range));
				} elseif ($field->field_type == "TEXT") {
					echo $form->textArea($profile, $field->varname);
				} else {
					echo $form->textFieldControlGroup($profile, $field->varname, ['size' => 60, 'maxlength' => (($field->field_size) ? $field->field_size : 255)]);
				}
			endforeach;
		endif;
	?>

    <?= BsHtml::submitButton($model->isNewRecord ? UserModule::t('Create') : UserModule::t('Save'), [
    	'color' => BsHtml::BUTTON_COLOR_PRIMARY]
    ); ?>

<?php $this->endWidget(); ?>