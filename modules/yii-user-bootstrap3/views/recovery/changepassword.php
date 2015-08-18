<?php 

$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Change password");
$this->breadcrumbs = [
	UserModule::t("Login") => ['/user/login'],
	UserModule::t("Change password"),
];
?>

<?= BsHtml::pageHeader(UserModule::t("Change password")) ?>

<?php $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', [
    'id' => 'change-password-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
    'htmlOptions' => ['enctype' => 'multipart/form-data'],
]); ?>

    <p class="help-block"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

    <?= $form->errorSummary($model); ?>

    <?= $form->passwordFieldControlGroup($model, 'password', [
        'maxlength' => 128,
    	'help' => UserModule::t("Minimal password length 4 symbols."),
    ]); ?>    

    <?= $form->passwordFieldControlGroup($model, 'verifyPassword', [
        'maxlength' => 128,
    	'help' => UserModule::t("Minimal password length 4 symbols."),
    ]); ?>   

    <?= BsHtml::submitButton(UserModule::t("Save"), [
    	'color' => BsHtml::BUTTON_COLOR_PRIMARY]
    ); ?>

<?php $this->endWidget(); ?>