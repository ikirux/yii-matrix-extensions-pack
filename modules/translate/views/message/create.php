<?php
/* @var $this MessageController */
/* @var $model Message */
?>

<?php
$this->breadcrumbs = [
	Yii::t('default', 'Messages') => ['index'],
	Yii::t('default', 'Crear'),
];

$this->menu = [
    ['icon' => 'glyphicon glyphicon-list', 'label' => Yii::t('default', 'Listar Message'), 'url' => ['index'], 'visible' => Yii::app()->user->checkAccess('message.index')],
];

$this->renderPartial('/default/_menu');
?>

<?= BsHtml::pageHeader(Yii::t('default', 'Crear Message')) ?>

<?php $this->renderPartial('_form', ['model' => $model]); ?>