<?php
/* @var $this LanguageController */
/* @var $model Language */
?>

<?php
$this->breadcrumbs = [
	Yii::t('default', 'Languages') => ['index'],
	Yii::t('default', 'Crear'),
];

$this->menu = [
    ['icon' => 'glyphicon glyphicon-list', 'label' => Yii::t('default', 'Listar Language'), 'url' => ['index'], 'visible' => Yii::app()->user->checkAccess('language.index')],
];

$this->renderPartial('/default/_menu');
?>

<?= BsHtml::pageHeader(Yii::t('default', 'Crear Language')) ?>

<?php $this->renderPartial('_form', ['model' => $model]); ?>