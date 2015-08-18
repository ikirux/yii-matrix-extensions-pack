<?php 

$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Profile");

$this->breadcrumbs = [
	UserModule::t("Profile") => ['profile'],
	UserModule::t("Edit"),
];

$this->menu = [
	((UserModule::isAdmin()) ? ['label' => UserModule::t('Manage Users'), 'url' => ['/user/admin']] : []),
    ['label' => UserModule::t('List User'), 'url' => ['/user'], 'visible' => UserModule::isAdmin()],
];

?>

<?= BsHtml::pageHeader(UserModule::t('Edit profile')) ?>

<?php $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', [
    'id' => 'profile-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
    'htmlOptions' => ['enctype' => 'multipart/form-data'],
]); ?>

    <p class="help-block"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

    <?= $form->errorSummary($model); ?>

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

    <?= $form->emailFieldControlGroup($model, 'email', ['maxlength' => 128]); ?> 

    <?= BsHtml::submitButton($model->isNewRecord ? UserModule::t('Create') : UserModule::t('Save'), [
    	'color' => BsHtml::BUTTON_COLOR_PRIMARY]
    ); ?>

<?php $this->endWidget(); ?>