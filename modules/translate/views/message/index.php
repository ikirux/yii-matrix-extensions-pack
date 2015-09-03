<?php
/* @var $this MessageController */
/* @var $model Message */


$this->breadcrumbs = [
    Yii::t('default', 'Messages') => ['index'],
    Yii::t('default', 'Listar'),
];

$this->menu = [
    ['icon' => 'glyphicon glyphicon-plus-sign', 'label' => Yii::t('default', 'Crear Message'), 'url' => ['create'], 'visible' => Yii::app()->user->checkAccess("message.create")],
    ['icon' => 'glyphicon glyphicon-book', 'label' => Yii::t('default', 'Generar archivo PDF'), 'url' => ['listPDF'], 'linkOptions' => ['target' => '_blank'], 'visible' => Yii::app()->user->checkAccess("message.listPdf")],
    ['icon' => 'glyphicon glyphicon-floppy-save', 'label' => Yii::t('default', 'Generar archivo Excel'), 'url' => ['listExcel'], 'linkOptions' => ['target' => '_blank'], 'visible' => Yii::app()->user->checkAccess("message.listExcel")],
];

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#language-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

$this->renderPartial('/default/_menu');
?>

<?= BsHtml::pageHeader(Yii::t('default', 'Listar Messages')); ?>
    
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?= BsHtml::button(Yii::t('default', 'Busqueda Avanzada'), ['class' => 'search-button', 'icon' => BsHtml::GLYPHICON_SEARCH, 'color' => BsHtml::BUTTON_COLOR_PRIMARY], '#'); ?>    
        </h3>
    </div>
    <div class="panel-body container-fluid">

        <div class="row">
            <div class="search-form col-lg-5" style="display:none">
                <?php $this->renderPartial('_search', [
                    'model' => $model,
                ]); ?>
            </div>
        </div>
        <!-- search-form -->

        <div class="row">
            <div class="col-lg-12">
            <?php $this->widget('bootstrap.widgets.BsGridView', [
    			'id' => 'message-grid',
    			'dataProvider' => $model->search(),
                'enableSorting' => false,
                'type' => BsHtml::GRID_TYPE_BORDERED,
    			'columns' => [
					'id',
					'language',             
                    [
                        'header' => 'Source',
                        'type' => 'raw',
                        'value' => '$data->r_sourceMessage->message',
                    ],
					'translation',
    				[
    					'class' => 'bootstrap.widgets.BsButtonColumn',
                        'afterDelete' => 'function(link, success, data){ if(success) $("#statusMsg").html(data); }',
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => [
                                'visible' => 'Yii::app()->user->checkAccess("message.view")', 
                                'url' => 'Yii::app()->createUrl("translate/message/view", ["id" => $data->id, "language" => $data->language])', 
                            ],                        
                            'update' => [
                                'visible' => 'Yii::app()->user->checkAccess("message.update")',
                                'url' => 'Yii::app()->createUrl("translate/message/update", ["id" => $data->id, "language" => $data->language])', 
                            ],
                            'delete' => [
                                'visible' => 'Yii::app()->user->checkAccess("message.delete")',
                                'url' => 'Yii::app()->createUrl("translate/message/delete", ["id" => $data->id, "language" => $data->language])', 
                            ],
                        ],                        
    				],
    			],
            ]); ?>
            </div>
        </div>
    </div>
</div>