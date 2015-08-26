<?php
/* @var $this OperationController|TaskController|RoleController */
/* @var $item CAuthItem */
/* @var $ancestorDp AuthItemDataProvider */
/* @var $descendantDp AuthItemDataProvider */
/* @var $formModel AddAuthItemForm */
/* @var $form TbActiveForm */
/* @var $childOptions array */

$this->breadcrumbs = [
    $this->capitalize($this->getTypeText(true)) => ['index'],
    $item->description,
];
?>

<div class="title-row clearfix">

    <h1 class="pull-left">
        <?= CHtml::encode($item->description); ?>
        <small><?= $this->getTypeText(); ?></small>
    </h1>

    <?= BsHtml::buttonGroup(
        [
            [
                'icon' => BsHtml::GLYPHICON_PENCIL,
                'label' => Yii::t('AuthModule.main', 'Edit'),
                'url' => array('update', 'name' => $item->name),
                'type' => 'GET',
            ],
            [
                'icon' => BsHtml::GLYPHICON_TRASH,
                'url' => ['delete', 'name' => $item->name],
                'type' => 'GET',
                'htmlOptions' => [
                    'confirm' => Yii::t('AuthModule.main', 'Are you sure you want to delete this item?'),
                ],
            ],            
        ],
        ['class' => 'pull-right']
    ); ?>
</div>

<?php $this->widget(
    'zii.widgets.CDetailView',
    [
        'htmlOptions' => [
            'class' => 'table table-striped table-condensed table-hover',
        ],        
        'data' => $item,
        'attributes' => [
            [
                'name' => 'name',
                'label' => Yii::t('AuthModule.main', 'System name'),
            ],
            [
                'name' => 'description',
                'label' => Yii::t('AuthModule.main', 'Description'),
            ],
            /*
            [
                'name' => 'bizrule',
                'label' => Yii::t('AuthModule.main', 'Business rule'),
            ],
            [
                'name' => 'data',
                'label' => Yii::t('AuthModule.main', 'Data'),
            ],
            */
        ],
    ]
); ?>


<hr/>

<div class="row">

    <div class="col-md-6">

        <h3>
            <?php echo Yii::t('AuthModule.main', 'Ancestors'); ?>
            <small><?php echo Yii::t('AuthModule.main', 'Permissions that inherit this item'); ?></small>
        </h3>

        <?php $this->widget('bootstrap.widgets.BsGridView', [
            'dataProvider' => $ancestorDp,
            'enableSorting' => false,
            'type' => BsHtml::GRID_TYPE_BORDERED,
            'emptyText' => Yii::t('AuthModule.main', 'This item does not have any ancestors.'),
            'hideHeader' => true,
            //'template'=>'{view}',
            'columns' => [
                [
                    'class' => 'AuthItemDescriptionColumn',
                    'itemName' => $item->name,
                ],
                [
                    'class' => 'webroot.themes.modules.auth.widgets.AuthItemTypeColumnBootStrap3',
                    'itemName' => $item->name,
                ],
                [
                    'class' => 'webroot.themes.modules.auth.widgets.AuthItemRemoveColumnBootStrap3',
                    'itemName' => $item->name,
                ],
            ],
        ]); ?>
    </div>

    <div class="col-md-6">

        <h3>
            <?php echo Yii::t('AuthModule.main', 'Descendants'); ?>
            <small><?php echo Yii::t('AuthModule.main', 'Permissions granted by this item'); ?></small>
        </h3>

        <?php $this->widget('bootstrap.widgets.BsGridView', [
            'dataProvider' => $descendantDp,
            'enableSorting' => false,
            'type' => BsHtml::GRID_TYPE_BORDERED,
            'emptyText' => Yii::t('AuthModule.main', 'This item does not have any descendants.'),
            'hideHeader' => true,
            //'template'=>'{view}',
            'columns' => [
                [
                    'class' => 'AuthItemDescriptionColumn',
                    'itemName' => $item->name,
                ],
                [
                    'class' => 'webroot.themes.modules.auth.widgets.AuthItemTypeColumnBootStrap3',
                    'itemName' => $item->name,
                ],
                [
                    'class' => 'webroot.themes.modules.auth.widgets.AuthItemRemoveColumnBootStrap3',
                    'itemName' => $item->name,
                ],
            ],
        ]); ?>

    </div>
</div>

<div class="row">

    <div class="col-md-6 col-md-offset-6">

        <?php if (!empty($childOptions)): ?>

            <h4><?php echo Yii::t('AuthModule.main', 'Add child'); ?></h4>

            <?php $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', [
                // Please note: When you enable ajax validation, make sure the corresponding
                // controller action is handling ajax validation correctly.
                // There is a call to performAjaxValidation() commented in generated controller code.
                // See class documentation of CActiveForm for details on this.
                'enableAjaxValidation' => false,
            ]); ?>

                <?= $form->dropDownListControlGroup($formModel, 'items', $childOptions, [
                    'label' => false,
                ]); ?>               

                <?= BsHtml::submitButton(Yii::t('AuthModule.main', 'Add'), [
                    'color' => BsHtml::BUTTON_COLOR_PRIMARY
                ]); ?>

            <?php $this->endWidget(); ?>

        <?php endif; ?>

    </div>

</div>