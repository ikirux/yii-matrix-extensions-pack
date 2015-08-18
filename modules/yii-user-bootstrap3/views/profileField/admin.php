<?php

$this->breadcrumbs = [
	UserModule::t('Profile Fields') => ['admin'],
	UserModule::t('Manage'),
];

$this->menu = [
    ['label' => UserModule::t('Create Profile Field'), 'url' => ['create']],
    ['label' => UserModule::t('Manage Profile Field'), 'url' => ['admin']],
    ['label' => UserModule::t('Manage Users'), 'url' => ['/user/admin']],
];

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('profileField-grid', {
        data: $(this).serialize()
    });
    return false;
});
");

?>

<?= BsHtml::pageHeader(UserModule::t('Manage Profile Fields')) ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= BsHtml::button(UserModule::t('Advanced Search'), ['class' => 'search-button', 'icon' => BsHtml::GLYPHICON_SEARCH, 'color' => BsHtml::BUTTON_COLOR_PRIMARY], '#'); ?></h3>
    </div>
    <div class="panel-body">

        <div class="search-form" style="display:none">
            <?php $this->renderPartial('_search', [
                'model' => $model,
            ]); ?>
        </div>
        <!-- search-form -->

        <?php $this->widget('bootstrap.widgets.BsGridView', [
        	'id' => 'profileField-grid',
			'dataProvider' => $model->search(),
            'enableSorting' => false,
            'type' => BsHtml::GRID_TYPE_BORDERED,
			'columns' => [
				'id',
				[
					'name' => 'varname',
					'type' => 'raw',
					'value' => 'UHtml::markSearch($data,"varname")',
				],
				[
					'name' => 'title',
					'value' => 'UserModule::t($data->title)',
				],
				[
					'name' => 'field_type',
					'value' => '$data->field_type',
					'filter' => ProfileField::itemAlias("field_type"),
				],
				'field_size',
				//'field_size_min',
				[
					'name' => 'required',
					'value' => 'ProfileField::itemAlias("required",$data->required)',
					'filter' => ProfileField::itemAlias("required"),
				],
				//'match',
				//'range',
				//'error_message',
				//'other_validator',
				//'default',
				'position',
				[
					'name' => 'visible',
					'value' => 'ProfileField::itemAlias("visible",$data->visible)',
					'filter' => ProfileField::itemAlias("visible"),
				],
				[
					'class' => 'bootstrap.widgets.BsButtonColumn',
                    'afterDelete' => 'function(link, success, data){ if(success) $("#statusMsg").html(data); }',
				],
			],
        ]); ?>
    </div>
</div>