<?php
/* @var $this LanguageController */
/* @var $model Language */
?>

<?php
$this->breadcrumbs = [
	'Languages' => ['index'],
	$model->translation => ['view', 'id' => $model->id, 'language' => $model->language],
	Yii::t('default', 'Actualizar'),
];

$this->menu = [
    ['icon' => 'glyphicon glyphicon-list', 'label' => Yii::t('default', 'Listar Language'), 'url' => ['index'], 'visible' => Yii::app()->user->checkAccess("language.index")],
	['icon' => 'glyphicon glyphicon-plus-sign', 'label' => Yii::t('default', 'Crear Language'), 'url' => ['create'], 'visible' => Yii::app()->user->checkAccess("language.create")],
    ['icon' => 'glyphicon glyphicon-list-alt', 'label' => Yii::t('default', 'Ver Language'), 'url' => ['view', 'id' => $model->id, "language" => $model->language], 'visible' => Yii::app()->user->checkAccess("language.view")],
];

$this->renderPartial('/default/_menu');
?>

<?= BsHtml::pageHeader(Yii::t('default', 'Actualizar Language')) ?>
<?php $this->renderPartial('_form', ['model' => $model]); ?>