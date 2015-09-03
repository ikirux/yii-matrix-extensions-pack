<?php
/* @var $this LanguageController */
/* @var $model Language */
/* @var $form BSActiveForm */
?>

<?php $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', [
    'id' => 'language-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
]); ?>
    <p class="help-block">Campos con <span class="required">*</span> son requeridos.</p>
    
    <div class="col-lg-5">
        <?= $form->errorSummary($model); ?>

        <?= $form->textFieldControlGroup($model, 'name', ['maxlength' => 250]); ?>
        <?= $form->dropDownListControlGroup($model, 'code', Yii::app()->request->languages, [
            'prompt' => $this->getPrompt(),
        ]); ?>
        <?= $form->dropDownListControlGroup($model, 'country_code_id', CountryCode::model()->getOptionList(), [
            'prompt' => $this->getPrompt(),
        ]); ?>
        <?= BsHtml::submitButton(Yii::t('default', 'Guardar'), ['color' => BsHtml::BUTTON_COLOR_PRIMARY]); ?>
    </div>
<?php $this->endWidget(); ?>
