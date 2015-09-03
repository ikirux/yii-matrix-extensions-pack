<?php
/* @var $this SourceMessageController */
/* @var $data SourceMessage */
?>

<div class="view">

	<b><?= CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?= CHtml::link(CHtml::encode($data->id), ['view', 'id' => $data->id]); ?>
	<br />

	<b><?= CHtml::encode($data->getAttributeLabel('category')); ?>:</b>
	<?= CHtml::encode($data->category); ?>
	<br />

	<b><?= CHtml::encode($data->getAttributeLabel('message')); ?>:</b>
	<?= CHtml::encode($data->message); ?>
	<br />


</div>