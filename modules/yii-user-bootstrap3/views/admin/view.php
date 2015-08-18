<?php

$this->breadcrumbs = [
	UserModule::t('Users') => ['admin'],
	$model->username,
];

$this->menu = [
    ['label' => UserModule::t('Create User'), 'url' => ['create']],
    ['label' => UserModule::t('Update User'), 'url' => ['update', 'id' => $model->id]],
    ['label' => UserModule::t('Delete User'), 'url' => '#', 'linkOptions' => ['submit' => ['delete', 'id' => $model->id], 'confirm' => UserModule::t('Are you sure to delete this item?')]],
    ['label' => UserModule::t('Manage Users'), 'url' => ['admin']],
    ['label' => UserModule::t('Manage Profile Field'), 'url' => ['profileField/admin']],
    ['label' => UserModule::t('List User'), 'url' => ['/user']],
];

?>

<?= BsHtml::pageHeader(UserModule::t('View User') . ' "' . $model->username . '"') ?>

<?php
 
$attributes = [
	'id',
	'username',
];
	
$profileFields = ProfileField::model()->forOwner()->sort()->findAll();
if ($profileFields) {
	foreach ($profileFields as $field) {
		array_push ($attributes, [
			'label' => UserModule::t($field->title),
			'name' => $field->varname,
			'type' => 'raw',
			'value' => (($field->widgetView($model->profile)) ? $field->widgetView($model->profile) : (($field->range) ? Profile::range($field->range, $model->profile->getAttribute($field->varname)) : $model->profile->getAttribute($field->varname))),
		]);
	}
}
	
array_push($attributes,
	'password',
	'email',
	'activkey',
	'create_at',
	'lastvisit_at',
	[
		'name' => 'superuser',
		'value' => User::itemAlias("AdminStatus", $model->superuser),
	],
	[
		'name' => 'status',
		'value' => User::itemAlias("UserStatus", $model->status),
	]
);
	
$this->widget('zii.widgets.CDetailView', [
	'htmlOptions' => [
		'class' => 'table table-striped table-condensed table-hover',
	],
	'data' => $model,
	'attributes' => $attributes,
]); ?>
