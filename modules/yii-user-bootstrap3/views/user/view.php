<?php

$this->breadcrumbs = [
	UserModule::t('Users') => ['index'],
	$model->username,
];

$this->layout = '//layouts/column2';
$this->menu = [
    ['label' => UserModule::t('List User'), 'url' => ['index']],
];

?>

<?= BsHtml::pageHeader(UserModule::t('View User') . ' "' . $model->username . '"') ?>

<?php 

// For all users
$attributes = [
	'username',
];
	
$profileFields = ProfileField::model()->forAll()->sort()->findAll();
if ($profileFields) {
	foreach ($profileFields as $field) {
		array_push($attributes, [
			'label' => UserModule::t($field->title),
			'name' => $field->varname,
			'value' => (($field->widgetView($model->profile)) ? $field->widgetView($model->profile) : (($field->range) ? Profile::range($field->range,$model->profile->getAttribute($field->varname)) : $model->profile->getAttribute($field->varname))),
		]);
	}
}

array_push($attributes,
	'create_at',
	array(
		'name' => 'lastvisit_at',
		'value' => (($model->lastvisit_at != '0000-00-00 00:00:00') ? $model->lastvisit_at:UserModule::t('Not visited')),
	)
);

$this->widget('zii.widgets.CDetailView', [
	'htmlOptions' => [
		'class' => 'table table-striped table-condensed table-hover',
	],
	'data' => $model,
	'attributes' => $attributes,
]); ?>
