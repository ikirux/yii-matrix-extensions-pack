<?php

$this->breadcrumbs = [
	(UserModule::t('Users')) => ['admin'],
	$model->username => ['view', 'id' => $model->id],
	(UserModule::t('Update')),
];

$this->menu = [
    ['label' => UserModule::t('Create User'), 'url' => ['create']],
    ['label' => UserModule::t('View User'), 'url' => ['view', 'id' => $model->id]],
    ['label' => UserModule::t('Manage Users'), 'url' => ['admin']],
    ['label' => UserModule::t('Manage Profile Field'), 'url' => ['profileField/admin']],
    ['label' => UserModule::t('List User'), 'url' => ['/user']],
];
?>

<?= BsHtml::pageHeader(UserModule::t('Update User') . " " . $model->id) ?>
<?php $this->renderPartial('_form', ['model' => $model, 'profile' => $profile]); ?>