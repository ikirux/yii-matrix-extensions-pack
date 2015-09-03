<?php
/* @var $this LanguageController */
/* @var $model Language */
?>

<?php
$this->breadcrumbs = [
	Yii::t('default', 'Messages') => ['index'],
	$model->translation,
];

$this->menu = [
    ['icon' => 'glyphicon glyphicon-list', 'label' => Yii::t('default', 'Listar Message'), 'url' => ['index'], 'visible' => Yii::app()->user->checkAccess("message.index")],
	['icon' => 'glyphicon glyphicon-plus-sign', 'label' => Yii::t('default', 'Crear Message'), 'url' => ['create'], 'visible' => Yii::app()->user->checkAccess("message.create")],
	['icon' => 'glyphicon glyphicon-edit', 'label' => Yii::t('default', 'Actualizar Message'), 'url' => ['update', 'id' => $model->id, "language" => $model->language], 'visible' => Yii::app()->user->checkAccess("message.update")],
	['icon' => 'glyphicon glyphicon-minus-sign', 'label' => Yii::t('default', 'Borrar Message'), 'url' => '#', 'linkOptions' => ['submit'=> ['delete', 'id' => $model->id, "language" => $model->language], 'confirm' => Yii::t('default', 'Está seguro que desea borrar este elemento?')], 'visible' => Yii::app()->user->checkAccess("message.delete")],
];

$this->renderPartial('/default/_menu');
?>

<?php echo BsHtml::pageHeader(Yii::t('default', 'Ver Message')); ?>

<?php $this->widget('zii.widgets.CDetailView', [
	'htmlOptions' => [
		'class' => 'table table-striped table-condensed table-hover',
	],
	'data' => $model,
	'attributes' => [
		'id',
		'language',
		'translation',
	],
]); ?>