<?php
/* @var $this OperationController|TaskController|RoleController */
/* @var $dataProvider AuthItemDataProvider */

$this->breadcrumbs = [
    $this->capitalize($this->getTypeText(true)),
];
?>

<h1><?php echo $this->capitalize($this->getTypeText(true)); ?></h1>

<?= BsHtml::linkButton(
        Yii::t('AuthModule.main', 'Add {type}', array('{type}' => $this->getTypeText())),
        [
            'color' => BsHtml::BUTTON_COLOR_PRIMARY,
            'url' => ['create'],
        ]
    ); 
?>
<hr />
<div class="panel panel-default">
    <div class="panel-body container-fluid">

        <div class="row">
            <div class="col-lg-12">
            <?php $this->widget('bootstrap.widgets.BsGridView', [
                'dataProvider' => $dataProvider,
                'enableSorting' => false,
                'type' => BsHtml::GRID_TYPE_BORDERED,
                'emptyText' => Yii::t('AuthModule.main', 'No {type} found.', ['{type}' => $this->getTypeText(true)]),
                //'template'=>'{view}',
                'columns' => [
                    [
                        'name' => 'name',
                        'type' => 'raw',
                        'header' => Yii::t('AuthModule.main', 'System name'),
                        'htmlOptions' => ['class' => 'item-name-column'],
                        'value' => "CHtml::link(\$data->name, array('view', 'name'=>\$data->name))",
                    ],
                    [
                        'name' => 'description',
                        'header' => Yii::t('AuthModule.main', 'Description'),
                        'htmlOptions' => ['class' => 'item-description-column'],
                    ],
                    [
                        'class' => 'bootstrap.widgets.BsButtonColumn',
                        'afterDelete' => 'function(link, success, data){ if(success) $("#statusMsg").html(data); }',
                        'viewButtonLabel' => Yii::t('AuthModule.main', 'View'),
                        'viewButtonUrl' => "Yii::app()->controller->createUrl('view', ['name'=>\$data->name])",
                        'updateButtonLabel' => Yii::t('AuthModule.main', 'Edit'),
                        'updateButtonUrl' => "Yii::app()->controller->createUrl('update', ['name'=>\$data->name])",
                        'deleteButtonLabel' => Yii::t('AuthModule.main', 'Delete'),
                        'deleteButtonUrl' => "Yii::app()->controller->createUrl('delete', ['name'=>\$data->name])",
                        'deleteConfirmation' => Yii::t('AuthModule.main', 'Are you sure you want to delete this item?'),                        
                    ],                    
                ],
            ]); ?>
            </div>
        </div>
    </div>
</div>