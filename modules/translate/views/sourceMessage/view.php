<?php
/* @var $this SourceMessageController */
/* @var $model SourceMessage */
?>

<?php
$this->breadcrumbs = [
	Yii::t('default', 'Source Messages') => ['index'],
	$model->id,
];

$this->menu = [
    ['icon' => 'glyphicon glyphicon-list', 'label' => Yii::t('default', 'Listar SourceMessage'), 'url' => ['index'], 'visible' => Yii::app()->user->checkAccess("sourcemessage.index")],
];

$this->renderPartial('/default/_menu');
?>

<?php echo BsHtml::pageHeader(Yii::t('default', 'Ver SourceMessage')); ?>

<?php $this->widget('zii.widgets.CDetailView', [
	'htmlOptions' => [
		'class' => 'table table-striped table-condensed table-hover',
	],
	'data' => $model,
	'attributes' => [
		'id',
		'category',
		'message',
	],
]); ?>