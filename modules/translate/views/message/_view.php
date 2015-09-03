<?php
/* @var $this LanguageController */
/* @var $data Language */
?>

<div class="view">

	<b><?= CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?= CHtml::link(CHtml::encode($data->id), ['view', 'id' => $data->id]); ?>
	<br />

	<b><?= CHtml::encode($data->getAttributeLabel('language')); ?>:</b>
	<?= CHtml::encode($data->language); ?>
	<br />

	<b><?= CHtml::encode($data->getAttributeLabel('translation')); ?>:</b>
	<?= CHtml::encode($data->translation); ?>
	<br />
	
</div>