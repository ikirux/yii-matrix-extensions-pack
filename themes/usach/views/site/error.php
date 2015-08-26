<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle = Yii::app()->name . ' - Error';
$this->breadcrumbs = [
	'Error',
];
?>

<h2>Error <?php echo $code; ?></h2>

<div class="error">
	<?= CHtml::encode($message); ?>
</div>