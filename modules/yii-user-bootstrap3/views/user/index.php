<?php

$this->breadcrumbs = [
	UserModule::t("Users"),
];

if (UserModule::isAdmin()) {
	$this->layout = '//layouts/column2';
	$this->menu = [
	    ['label' => UserModule::t('Manage Users'), 'url' => ['/user/admin']],
	    ['label' => UserModule::t('Manage Profile Field'), 'url' => ['profileField/admin']],
	];
}
?>

<?= BsHtml::pageHeader(UserModule::t("List User")) ?>

<div class="panel panel-default">
    <div class="panel-body">
        <?php $this->widget('bootstrap.widgets.BsGridView', [
			'id' => 'usuario-grid',
			'dataProvider' => $dataProvider,
            'enableSorting' => false,
            'type' => BsHtml::GRID_TYPE_BORDERED,
			'columns' => [
				[
					'name' => 'username',
					'type'=>'raw',
					'value' => 'CHtml::link(CHtml::encode($data->username), ["user/view", "id" => $data->id])',
				],
				'create_at',
				'lastvisit_at',
			],
        ]); ?>
    </div>
</div>
