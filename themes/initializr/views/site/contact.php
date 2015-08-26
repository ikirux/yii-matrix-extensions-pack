<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name . ' - Contactanos';
$this->breadcrumbs = [
	'Contacto',
];
?>

<h1>Contactanos</h1>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

	<div class="flash-success">
		<?= Yii::app()->user->getFlash('contact'); ?>
	</div>

<?php else: ?>

	<?php
	$form = $this->beginWidget('bootstrap.widgets.BsActiveForm', [
	    'enableAjaxValidation' => true,
	    'id' => 'contact-form',
	]);
	?>

	<fieldset>
	    <legend>Por favor complete el siguiente formulario para comunicarse con nosotros.</legend>
	    <p class="note">Campos con * son requeridos.</p>
	    <div class="col-lg-5">
			<?= $form->textFieldControlGroup($model, 'name'); ?>
			<?= $form->textFieldControlGroup($model, 'email'); ?>
			<?= $form->textFieldControlGroup($model, 'subject', ['maxlength' => 128]); ?>
			<?= $form->textAreaControlGroup($model, 'body'); ?>
			<?php if(CCaptcha::checkRequirements()): ?>
				<div class="form-group validating">
					<?= $form->labelEx($model, 'verifyCode'); ?>
					<div>
						<?php $this->widget('CCaptcha', [
							'buttonLabel' => 'Obtener un nuevo código',
						]); ?>
						<?= $form->textField($model, 'verifyCode'); ?>
					</div>
					<p class="help-block">Ingrese las letras como se muestran en la imagen superior.
					<br/>No se diferencia entre mayúsculas ni minúsculas.</p>
					<?= $form->error($model, 'verifyCode'); ?>
				</div>
			<?php endif; ?>
			<?= BsHtml::submitButton('Enviar', [
			    'color' => BsHtml::BUTTON_COLOR_PRIMARY
			]); ?>
		</div>
	</fieldset>

	<?php
	$this->endWidget();
	?>
<?php endif; ?>