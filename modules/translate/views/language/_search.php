<?php
/* @var $this LanguageController */
/* @var $model Language */
/* @var $form BSActiveForm */
?>

<?php $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', [
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
]); ?>
    <?= $form->textFieldControlGroup($model, 'id'); ?>
    <?= $form->textFieldControlGroup($model, 'name', ['maxlength' => 250]); ?>
    <?= $form->dropDownListControlGroup($model, 'code', Yii::app()->request->languages, [
        'prompt' => $this->getPrompt(),
    ]); ?>
    <?= $form->dropDownListControlGroup($model, 'country_code_id', CountryCode::model()->getOptionList(), [
        'prompt' => $this->getPrompt(),
    ]); ?>    
    
    <div class="form-actions" style="padding-bottom: 1em;">
        <?= BsHtml::submitButton(Yii::t('default', 'Buscar'), ['color' => BsHtml::BUTTON_COLOR_PRIMARY,]);?>
    </div>
<?php $this->endWidget(); ?>
