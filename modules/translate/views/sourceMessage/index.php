<?php
/* @var $this SourceMessageController */
/* @var $model SourceMessage */

$this->breadcrumbs = [
    Yii::t('default', 'Source Messages') => ['index'],
    Yii::t('default', 'Listar'),
];

$this->menu = [
    ['icon' => 'glyphicon glyphicon-book', 'label' => Yii::t('default', 'Generar archivo PDF'), 'url' => ['listPDF'], 'linkOptions' => ['target' => '_blank'], 'visible' => Yii::app()->user->checkAccess("sourcemessage.listPdf")],
    ['icon' => 'glyphicon glyphicon-floppy-save', 'label' => Yii::t('default', 'Generar archivo Excel'), 'url' => ['listExcel'], 'linkOptions' => ['target' => '_blank'], 'visible' => Yii::app()->user->checkAccess("sourcemessage.listExcel")],    
];

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#source-message-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

$this->renderPartial('/default/_menu');
?>

<?= BsHtml::pageHeader(Yii::t('default', 'Listar Source Messages')); ?>
    
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
    			'id' => 'source-message-grid',
    			'dataProvider' => $model->search(),
                'enableSorting' => false,
                'type' => BsHtml::GRID_TYPE_BORDERED,
    			'columns' => [
					'id',
					'category',
					'message',
    				[
    					'class' => 'bootstrap.widgets.BsButtonColumn',
                        'template' => '{view}',
                        'buttons' => [
                            'view' => [
                                'visible' => 'Yii::app()->user->checkAccess("sourcemessage.view")',                              
                            ],                        
                        ],                        
    				],
    			],
            ]); ?>
            </div>
        </div>
    </div>
</div>