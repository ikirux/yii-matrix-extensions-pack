<?php

$this->breadcrumbs = [
	UserModule::t('Profile Fields') => ['admin'],
	UserModule::t($model->title),
];

$this->menu = [
    ['label' => UserModule::t('Create Profile Field'), 'url' => ['create']],
    ['label' => UserModule::t('Update Profile Field'), 'url' => ['update', 'id' => $model->id]],
    ['label' => UserModule::t('Delete Profile Field'), 'url' => '#', 'linkOptions' => ['submit' => ['delete', 'id' => $model->id], 'confirm' => UserModule::t('Are you sure to delete this item?')]],
    ['label' => UserModule::t('Manage Profile Field'), 'url' => ['admin']],
    ['label' => UserModule::t('Manage Users'), 'url' => ['/user/admin']],
];

?>

<?= BsHtml::pageHeader(UserModule::t('View Profile Field #') . $model->varname) ?>

<?php $this->widget('zii.widgets.CDetailView', [
	'htmlOptions' => [
		'class' => 'table table-striped table-condensed table-hover',
	],
	'data' => $model,
	'attributes' => [
		'id',
		'varname',
		'title',
		'field_type',
		'field_size',
		'field_size_min',
		'required',
		'match',
		'range',
		'error_message',
		'other_validator',
		'widget',
		'widgetparams',
		'default',
		'position',
		'visible',	
	],
]); ?>
