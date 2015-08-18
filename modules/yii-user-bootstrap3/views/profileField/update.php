<?php

$this->breadcrumbs = [
	UserModule::t('Profile Fields') => ['admin'],
	$model->title => ['view', 'id' => $model->id],
	UserModule::t('Update'),
];

$this->menu = [
    ['label' => UserModule::t('Create Profile Field'), 'url' => ['create']],
    ['label' => UserModule::t('View Profile Field'), 'url' => ['view', 'id' => $model->id]],
    ['label' => UserModule::t('Manage Profile Field'), 'url' => ['admin']],
    ['label' => UserModule::t('Manage Users'), 'url' => ['/user/admin']],
];

?>

<?= BsHtml::pageHeader(UserModule::t('Update Profile Field ') . $model->id) ?>
<?= $this->renderPartial('_form', ['model' => $model]); ?>