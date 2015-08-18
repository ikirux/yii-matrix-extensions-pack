<?php

$this->breadcrumbs = [
	UserModule::t('Users') => ['/user'],
	UserModule::t('Manage'),
];

$this->menu = [
    ['label' => UserModule::t('Create User'), 'url' => ['create']],
    ['label' => UserModule::t('Manage Users'), 'url' => ['admin']],
    ['label' => UserModule::t('Manage Profile Field'), 'url' => ['profileField/admin']],
    ['label' => UserModule::t('List User'), 'url' => ['/user']],
];

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});	
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('user-grid', {
        data: $(this).serialize()
    });
    return false;
});
");

?>

<?= BsHtml::pageHeader(UserModule::t("Manage Users")) ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= BsHtml::button('Busqueda Avanzada', ['class' => 'search-button', 'icon' => BsHtml::GLYPHICON_SEARCH, 'color' => BsHtml::BUTTON_COLOR_PRIMARY], '#'); ?></h3>
    </div>
    <div class="panel-body">

        <div class="search-form" style="display:none">
            <?php $this->renderPartial('_search', [
                'model' => $model,
            ]); ?>
        </div>
        <!-- search-form -->

        <?php $this->widget('bootstrap.widgets.BsGridView', [
			'id' => 'user-grid',
			'dataProvider' => $model->search(),
            'enableSorting' => false,
            'type' => BsHtml::GRID_TYPE_BORDERED,
			'columns' => [
				[
					'name' => 'id',
					'type'=>'raw',
					'value' => 'CHtml::link(CHtml::encode($data->id),array("admin/update","id"=>$data->id))',
				],
				[
					'name' => 'username',
					'type'=>'raw',
					'value' => 'CHtml::link(UHtml::markSearch($data,"username"),array("admin/view","id"=>$data->id))',
				],
				[
					'name'=>'email',
					'type'=>'raw',
					'value'=>'CHtml::link(UHtml::markSearch($data,"email"), "mailto:".$data->email)',
				],
				'create_at',
				'lastvisit_at',
				[
					'name'=>'superuser',
					'value'=>'User::itemAlias("AdminStatus",$data->superuser)',
					'filter'=>User::itemAlias("AdminStatus"),
				],
				[
					'name'=>'status',
					'value'=>'User::itemAlias("UserStatus",$data->status)',
					'filter' => User::itemAlias("UserStatus"),
				],
				[
					'class' => 'bootstrap.widgets.BsButtonColumn',
                    'afterDelete' => 'function(link, success, data){ if(success) $("#statusMsg").html(data); }',
				],
			],
        ]); ?>
    </div>
</div>
