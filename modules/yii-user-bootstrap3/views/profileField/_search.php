<?php $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', [
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
]); ?>

    <?= $form->textFieldControlGroup($model, 'id'); ?>
    <?= $form->textFieldControlGroup($model, 'varname', ['maxlength' => 50]); ?>
    <?= $form->dropDownListControlGroup($model, 'field_type', ProfileField::itemAlias('field_type')); ?>
    <?= $form->textFieldControlGroup($model, 'field_size'); ?>
    <?= $form->textFieldControlGroup($model, 'field_size_min'); ?>
    <?= $form->dropDownListControlGroup($model, 'required', ProfileField::itemAlias('required')); ?>
    <?= $form->textFieldControlGroup($model, 'match', ['maxlength' => 255]); ?>
    <?= $form->textFieldControlGroup($model, 'range', ['maxlength' => 255]); ?>
    <?= $form->textFieldControlGroup($model, 'error_message', ['maxlength' => 255]); ?>
    <?= $form->textFieldControlGroup($model, 'other_validator', ['maxlength' => 5000]); ?>
    <?= $form->textFieldControlGroup($model, 'default', ['maxlength' => 255]); ?>
    <?= $form->textFieldControlGroup($model, 'widget', ['maxlength' => 255]); ?>
    <?= $form->textFieldControlGroup($model, 'widgetparams', ['maxlength' => 5000]); ?>
    <?= $form->textFieldControlGroup($model, 'position'); ?>
    <?= $form->dropDownListControlGroup($model, 'visible', ProfileField::itemAlias('visible')); ?>

    <div class="form-actions" style="padding-bottom: 1em;">
        <?= BsHtml::submitButton(UserModule::t('Search'), ['color' => BsHtml::BUTTON_COLOR_PRIMARY,]);?>
    </div>

<?php $this->endWidget(); ?>