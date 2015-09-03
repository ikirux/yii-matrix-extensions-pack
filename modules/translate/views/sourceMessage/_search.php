<?php
/* @var $this SourceMessageController */
/* @var $model SourceMessage */
/* @var $form BSActiveForm */
?>

<?php $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', [
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
]); ?>
    <?= $form->textFieldControlGroup($model, 'id'); ?>
    <?= $form->textFieldControlGroup($model, 'category', ['maxlength' => 32]); ?>
    
    <div class="form-actions" style="padding-bottom: 1em;">
        <?= BsHtml::submitButton(Yii::t('default', 'Buscar'), ['color' => BsHtml::BUTTON_COLOR_PRIMARY,]);?>
    </div>
<?php $this->endWidget(); ?>
