<?php
/* @var $this SiteController */
/* @var $model LoginForm */

$this->pageTitle = Yii::app()->name . ' - Ingreso';
$this->breadcrumbs = [
	'Ingreso',
];
?>

<?php
$form = $this->beginWidget('bootstrap.widgets.BsActiveForm', [
    'enableAjaxValidation' => true,
    'id' => 'user_form',
]);
?>

<h1>Ingreso</h1>

<fieldset>
    <legend>Por favor ingresa tus credenciales de acceso:</legend>
    <p class="note">Campos con * son requeridos.</p>
    <div class="col-lg-5">
		<?= $form->textFieldControlGroup($model, 'username'); ?>
		<?= $form->passwordFieldControlGroup($model, 'password', [
			'help' => 'Consejo: Se puede ingresar con <kbd>demo</kbd>/<kbd>demo</kbd> o <kbd>admin</kbd>/<kbd>admin</kbd>.',
		]); ?>
		<?= $form->checkBoxControlGroup($model, 'rememberMe'); ?>
		<?= BsHtml::submitButton('Enviar', [
		    	'color' => BsHtml::BUTTON_COLOR_PRIMARY,
		]); ?>
	</div>
</fieldset>
<?php
$this->endWidget();
?>