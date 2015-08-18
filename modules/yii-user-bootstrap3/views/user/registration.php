<?php 

$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Registration");
$this->breadcrumbs = [
	UserModule::t("Registration"),
];
?>

<?= BsHtml::pageHeader(UserModule::t("Registration")) ?>

<?php $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', [
    'id' => 'registration-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
    'htmlOptions' => ['enctype' => 'multipart/form-data'],
	'clientOptions' => [
		'validateOnSubmit' => true,
	],    
]); ?>

    <p class="help-block"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

    <?= $form->errorSummary($model); ?>

    <?= $form->textFieldControlGroup($model, 'username', ['maxlength' => 20]); ?>
    <?= $form->passwordFieldControlGroup($model, 'password', [
    	'maxlength' => 128,
    	'help' => UserModule::t("Minimal password length 4 symbols."),
    ]); ?>  
    <?= $form->passwordFieldControlGroup($model, 'verifyPassword', [
    	'maxlength' => 128,
    	'help' => UserModule::t("Minimal password length 4 symbols."),
    ]); ?>  
    <?= $form->emailFieldControlGroup($model, 'email', ['maxlength' => 128]); ?> 

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

	<?php if (UserModule::doCaptcha('registration')): ?>
		<div class="row">
			<?php $this->widget('CCaptcha'); ?>
		</div>
		<?= $form->textFieldControlGroup($model, 'verifyCode', [
			'maxlength' => 10,
			'help' => UserModule::t("Please enter the letters as they are shown in the image above.") . '\n' .
					UserModule::t("Letters are not case-sensitive."),
		]); ?>			
	<?php endif; ?>

    <?= BsHtml::submitButton(UserModule::t("Register"), [
    	'color' => BsHtml::BUTTON_COLOR_PRIMARY]
    ); ?>

<?php $this->endWidget(); ?>