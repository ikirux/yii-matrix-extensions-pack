<?php
/* @var $this OperationController|TaskController|RoleController */
/* @var $model AuthItemForm */
/* @var $form TbActiveForm */

$this->breadcrumbs = [
    $this->capitalize($this->getTypeText(true)) => ['index'],
    Yii::t('AuthModule.main', 'New {type}', ['{type}' => $this->getTypeText()]),
];
?>

<h1><?php echo Yii::t('AuthModule.main', 'New {type}', array('{type}' => $this->getTypeText())); ?></h1>

<?php $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', [
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
]); ?>
    <p class="help-block">Campos con <span class="required">*</span> son requeridos.</p>
    <div class="col-lg-5">
        <?= $form->hiddenField($model, 'type'); ?>
        <?= $form->textFieldControlGroup($model, 'name'); ?>
        <?= $form->textFieldControlGroup($model, 'description'); ?>
        <?= BsHtml::submitButton('Guardar', ['color' => BsHtml::BUTTON_COLOR_PRIMARY]); ?>   
        <?= BsHtml::linkButton(Yii::t('AuthModule.main', 'Cancel'), [
                'color' => BsHtml::BUTTON_COLOR_LINK,
                'url' => ['index'],
            ]
        ); ?>
    </div>
<?php $this->endWidget(); ?>