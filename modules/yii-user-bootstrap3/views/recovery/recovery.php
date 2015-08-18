<?php 

$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Restore");
$this->breadcrumbs = [
	UserModule::t("Login") => ['/user/login'],
	UserModule::t("Restore"),
];
?>

<?= BsHtml::pageHeader(UserModule::t("Restore")) ?>

<?php $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', [
    'id' => 'recovery-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
    'htmlOptions' => ['enctype' => 'multipart/form-data'],
]); ?>

    <p class="help-block"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

    <?= $form->errorSummary($model); ?>

    <?= $form->textFieldControlGroup($model, 'login_or_email', [
        'maxlength' => 128,
    	'help' => UserModule::t("Please enter your login or email addres."),
    ]); ?>

    <?= BsHtml::submitButton(UserModule::t("Restore"), [
    	'color' => BsHtml::BUTTON_COLOR_PRIMARY]
    ); ?>

<?php $this->endWidget(); ?>