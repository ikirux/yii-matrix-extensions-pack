<?php
/* @var $this MessageController */
/* @var $model Message */
/* @var $form BSActiveForm */
?>

<?php $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', [
    'id' => 'message-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
]); ?>
    <p class="help-block">Campos con <span class="required">*</span> son requeridos.</p>
    
    <div class="col-lg-5">
        <?= $form->errorSummary($model); ?>
        <?= $form->dropDownListControlGroup($model, 'id', SourceMessage::model()->getOptionList(), [
            'prompt' => $this->getPrompt()
        ]); ?>
        <?= $form->dropDownListControlGroup($model, 'language', Language::model()->getOptionList(), [
            'prompt' => $this->getPrompt()
        ]); ?>
        <?= $form->textFieldControlGroup($model, 'translation', ['maxlength' => 300]); ?>
        <?= BsHtml::submitButton(Yii::t('default', 'Guardar'), ['color' => BsHtml::BUTTON_COLOR_PRIMARY]); ?>
    </div>
<?php $this->endWidget(); ?>
