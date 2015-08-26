<?php
/* @var $this AssignmentController */
/* @var $model User */
/* @var $authItemDp AuthItemDataProvider */
/* @var $formModel AddAuthItemForm */
/* @var $form TbActiveForm */
/* @var $assignmentOptions array */

$this->breadcrumbs = array(
    Yii::t('AuthModule.main', 'Assignments') => array('index'),
    CHtml::value($model, $this->module->userNameColumn),
);
?>

<h1>
    <?= CHtml::encode(CHtml::value($model, $this->module->userNameColumn)); ?>
    <small><?= Yii::t('AuthModule.main', 'Assignments'); ?></small>
</h1>

<div class="row">
    <div class="span6">
        <h3>
            <?= Yii::t('AuthModule.main', 'Permissions'); ?>
            <small><?= Yii::t('AuthModule.main', 'Items assigned to this user'); ?></small>
        </h3>

        <?php $this->widget('bootstrap.widgets.BsGridView', [
            'dataProvider' => $authItemDp,
            'enableSorting' => false,
            'emptyText' => Yii::t('AuthModule.main', 'This user does not have any assignments.'),
            'type' => BsHtml::GRID_TYPE_BORDERED,
            'emptyText' => Yii::t('AuthModule.main', 'No assignments found.'),
            'hideHeader' => true,
            //'template'=>'{view}',
            'columns' => [
                [
                    'class' => 'AuthItemDescriptionColumn',
                    'active' => true,
                ],
                [
                    'class' => 'webroot.themes.modules.auth.widgets.AuthItemTypeColumnBootStrap3',
                    'active' => true,
                ],
                [
                    'class' => 'webroot.themes.modules.auth.widgets.AuthAssignmentRevokeColumnBootStrap3',
                    'userId' => $model->{$this->module->userIdColumn},
                ],
            ],
        ]); ?>

        <?php if (!empty($assignmentOptions)): ?>

            <h4><?php echo Yii::t('AuthModule.main', 'Assign permission'); ?></h4>

            <?php $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', [
                // Please note: When you enable ajax validation, make sure the corresponding
                // controller action is handling ajax validation correctly.
                // There is a call to performAjaxValidation() commented in generated controller code.
                // See class documentation of CActiveForm for details on this.
                'enableAjaxValidation' => false,
            ]); ?>

                <?= $form->dropDownListControlGroup($formModel, 'items', $assignmentOptions, [
                    'label' => false
                ]); ?>

                <?= BsHtml::submitButton(Yii::t('AuthModule.main', 'Assign'), [
                    'color' => BsHtml::BUTTON_COLOR_PRIMARY
                ]); ?>   
            <?php $this->endWidget(); ?>

        <?php endif; ?>
    </div>
</div>