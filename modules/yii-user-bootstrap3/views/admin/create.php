<?php

$this->breadcrumbs = [
	UserModule::t('Users') => ['admin'],
	UserModule::t('Create'),
];

$this->menu = [
    ['label' => UserModule::t('Manage Users'), 'url' => ['admin']],
    ['label' => UserModule::t('Manage Profile Field'), 'url' => ['profileField/admin']],
    ['label' => UserModule::t('List User'), 'url' => ['/user']],
];

?>

<?= BsHtml::pageHeader(UserModule::t("Create User")) ?>
<?php $this->renderPartial('_form', ['model' => $model, 'profile' => $profile]); ?>