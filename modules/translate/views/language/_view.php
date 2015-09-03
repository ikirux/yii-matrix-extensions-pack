<?php
/* @var $this LanguageController */
/* @var $data Language */
?>

<div class="view">

	<b><?= CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?= CHtml::link(CHtml::encode($data->id), ['view', 'id' => $data->id]); ?>
	<br />

	<b><?= CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?= CHtml::encode($data->name); ?>
	<br />

	<b><?= CHtml::encode($data->getAttributeLabel('code')); ?>:</b>
	<?= CHtml::encode($data->code); ?>
	<br />

	<b><?= CHtml::encode($data->getAttributeLabel('country_code_id')); ?>:</b>
	<?= CHtml::encode($data->r_countryCode->nombre . " (" . $data->r_countryCode->code . ")"); ?>
	<br />
</div>