<?php $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', [
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
]); ?>

    <?= $form->textFieldControlGroup($model, 'id'); ?>
    <?= $form->textFieldControlGroup($model, 'username', ['maxlength' => 20]); ?>
    <?= $form->textFieldControlGroup($model, 'email', ['maxlength' => 128]); ?>
    <?= $form->textFieldControlGroup($model, 'activkey', ['maxlength' => 128]); ?>
    <?= $form->textFieldControlGroup($model, 'create_at'); ?>
    <?= $form->textFieldControlGroup($model, 'lastvisit_at'); ?>
    <?= $form->dropDownListControlGroup($model, 'superuser', $model->itemAlias('AdminStatus')); ?>
    <?= $form->dropDownListControlGroup($model, 'status', $model->itemAlias('UserStatus')); ?>


    <div class="form-actions" style="padding-bottom: 1em;">
        <?= BsHtml::submitButton('Buscar', ['color' => BsHtml::BUTTON_COLOR_PRIMARY,]);?>
    </div>

<?php $this->endWidget(); ?>