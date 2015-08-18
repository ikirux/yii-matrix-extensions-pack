<?php 

$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Profile");
$this->breadcrumbs = [
	UserModule::t("Profile"),
];

$this->menu = [
	((UserModule::isAdmin()) ? ['label' => UserModule::t('Manage Users'), 'url' => ['/user/admin']] : []),
    ['label' => UserModule::t('List User'), 'url' => ['/user'], 'visible' => UserModule::isAdmin()],
    ['label' => UserModule::t('Edit'), 'url' => ['edit']],
]; 

?>

<?= BsHtml::pageHeader(UserModule::t('Your profile')) ?>

<table class="table table-striped table-condensed table-hover">
	<tr class="odd">
		<th><?php echo CHtml::encode($model->getAttributeLabel('username')); ?></th>
	    <td><?php echo CHtml::encode($model->username); ?></td>
	</tr>
	<?php 
		if ($profileFields = ProfileField::model()->forOwner()->sort()->findAll()):
			foreach($profileFields as $key => $field):
	?>
		<tr class="<?php echo ($key % 2 == 0) ? 'odd' : 'even'; ?>">
			<th><?php echo CHtml::encode(UserModule::t($field->title)); ?></th>
	    	<td><?php echo (($field->widgetView($profile))?$field->widgetView($profile):CHtml::encode((($field->range)?Profile::range($field->range,$profile->getAttribute($field->varname)):$profile->getAttribute($field->varname)))); ?></td>
		</tr>
	<?php
			endforeach;
		endif;
	?>
	<tr class="even">
		<th><?php echo CHtml::encode($model->getAttributeLabel('email')); ?></th>
    	<td><?php echo CHtml::encode($model->email); ?></td>
	</tr>
	<tr class="odd">
		<th><?php echo CHtml::encode($model->getAttributeLabel('create_at')); ?></th>
    	<td><?php echo $model->create_at; ?></td>
	</tr>
	<tr class="even">
		<th><?php echo CHtml::encode($model->getAttributeLabel('lastvisit_at')); ?></th>
    	<td><?php echo $model->lastvisit_at; ?></td>
	</tr>
	<tr class="odd">
		<th><?php echo CHtml::encode($model->getAttributeLabel('status')); ?></th>
    	<td><?php echo CHtml::encode(User::itemAlias("UserStatus",$model->status)); ?></td>
	</tr>
</table>
