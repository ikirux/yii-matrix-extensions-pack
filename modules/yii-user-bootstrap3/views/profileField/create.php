<?php

$this->breadcrumbs = [
	UserModule::t('Profile Fields') => ['admin'],
	UserModule::t('Create'),
];

$this->menu = [
    ['label' => UserModule::t('Manage Profile Field'), 'url' => ['admin']],
    ['label' => UserModule::t('Manage Users'), 'url' => ['/user/admin']],
];

?>

<?= BsHtml::pageHeader(UserModule::t('Create Profile Field')) ?>
<?= $this->renderPartial('_form', ['model' => $model]); ?>