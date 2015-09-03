<?php
/* @var $this LanguageController */
/* @var $model Language */
?>

<?php
$this->breadcrumbs = [
	Yii::t('default', 'Languages') => ['index'],
	$model->name,
];

$this->menu = [
    ['icon' => 'glyphicon glyphicon-list', 'label' => Yii::t('default', 'Listar Language'), 'url' => ['index'], 'visible' => Yii::app()->user->checkAccess("language.index")],
	['icon' => 'glyphicon glyphicon-plus-sign', 'label' => Yii::t('default', 'Crear Language'), 'url' => ['create'], 'visible' => Yii::app()->user->checkAccess("language.create")],
	['icon' => 'glyphicon glyphicon-edit', 'label' => Yii::t('default', 'Actualizar Language'), 'url' => ['update', 'id'=>$model->id], 'visible' => Yii::app()->user->checkAccess("language.update")],
	['icon' => 'glyphicon glyphicon-minus-sign', 'label' => Yii::t('default', 'Borrar Language'), 'url' => '#', 'linkOptions' => ['submit'=> ['delete', 'id' => $model->id], 'confirm' => Yii::t('default', 'Está seguro que desea borrar este elemento?')], 'visible' => Yii::app()->user->checkAccess("language.delete")],
];

$this->renderPartial('/default/_menu');
?>

<?php echo BsHtml::pageHeader(Yii::t('default', 'Ver Language')); ?>

<?php $this->widget('zii.widgets.CDetailView', [
	'htmlOptions' => [
		'class' => 'table table-striped table-condensed table-hover',
	],
	'data' => $model,
	'attributes' => [
		'id',
		'name',
		'code',
        [
            'name' => 'country_code_id',
            'type' => 'raw',
            'value' => $model->r_countryCode->nombre . " (" . $model->r_countryCode->code . ")",
        ],		
	],
]); ?>