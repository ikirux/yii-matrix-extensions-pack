<?php
/* @var $this MessageController */
/* @var $model Message */
/* @var $form BSActiveForm */
?>

<?php $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', [
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
]); ?>
    <?= $form->textFieldControlGroup($model, 'id'); ?>
    <?= $form->dropDownListControlGroup($model, 'language', Language::model()->getOptionList(), [
        'prompt' => $this->getPrompt()
    ]); ?>    
    <?= $form->textFieldControlGroup($model, 'translation', ['maxlength' => 3]); ?>
    
    <div class="form-actions" style="padding-bottom: 1em;">
        <?= BsHtml::submitButton(Yii::t('default', 'Buscar'), ['color' => BsHtml::BUTTON_COLOR_PRIMARY,]);?>
    </div>
<?php $this->endWidget(); ?>
