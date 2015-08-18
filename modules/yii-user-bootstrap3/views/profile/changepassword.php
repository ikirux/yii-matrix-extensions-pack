<?php 

$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Change password");
$this->breadcrumbs = [
	UserModule::t("Profile") => ['/user/profile'],
	UserModule::t("Change password"),
];

$this->menu = [
	((UserModule::isAdmin()) ? ['label' => UserModule::t('Manage Users'), 'url' => ['/user/admin']] : []),
    ['label' => UserModule::t('List User'), 'url' => ['/user'], 'visible' => UserModule::isAdmin()],
    ['label' => UserModule::t('Edit'), 'url' => ['edit']],
];

?>

<?= BsHtml::pageHeader(UserModule::t("Change password")) ?>

<div class="row">
    <div class="col-md-6">
        <?php $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', [
            'id' => 'changepassword-form',
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => false,
            'htmlOptions' => ['enctype' => 'multipart/form-data'],
        ]); ?>

            <p class="help-block"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

            <?= $form->errorSummary($model); ?>

            <?= $form->passwordFieldControlGroup($model, 'oldPassword', ['maxlength' => 128]); ?>    
            <?= $form->passwordFieldControlGroup($model, 'password', [
            	'maxlength' => 128,
            	'help' => UserModule::t("Minimal password length 4 symbols."),
            ]); ?>
            <?= $form->passwordFieldControlGroup($model, 'verifyPassword', ['maxlength' => 128]); ?>  

            <?= BsHtml::submitButton(UserModule::t("Save"), [
            	'color' => BsHtml::BUTTON_COLOR_PRIMARY]
            ); ?>

        <?php $this->endWidget(); ?>
    <div>
</div>