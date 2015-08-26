<?php
/* @var $this OperationController|TaskController|RoleController */
/* @var $model AuthItemForm */
/* @var $item CAuthItem */
/* @var $form TbActiveForm */

$this->breadcrumbs = [
    $this->capitalize($this->getTypeText(true)) => ['index'],
    $item->description => ['view', 'name' => $item->name],
    Yii::t('AuthModule.main', 'Edit'),
];
?>

<h1>
    <?php echo CHtml::encode($item->description); ?>
    <small><?php echo $this->getTypeText(); ?></small>
</h1>

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
        <?= $form->textFieldControlGroup(
            $model,
            'name',
            [
                'disabled' => true,
                'help' => Yii::t('AuthModule.main', 'System name cannot be changed after creation.'),
            ]
        ); ?>
        <?= $form->textFieldControlGroup($model, 'description'); ?>

        <?= BsHtml::submitButton('Guardar', ['color' => BsHtml::BUTTON_COLOR_PRIMARY]); ?>   
        <?= BsHtml::linkButton(Yii::t('AuthModule.main', 'Cancel'), [
                'color' => BsHtml::BUTTON_COLOR_LINK,
                'url' => ['index'],
            ]
        ); ?>
    </div>
<?php $this->endWidget(); ?>