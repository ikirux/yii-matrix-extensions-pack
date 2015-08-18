<?php 

$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Login"); ?>

<?= BsHtml::pageHeader($title) ?>

<div class="form">
	<?= $content; ?>
</div><!-- yiiForm -->