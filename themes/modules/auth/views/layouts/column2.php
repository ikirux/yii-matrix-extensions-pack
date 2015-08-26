<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>

<div class="container">
	<?php if (!empty($this->menu)): ?>
	    <div class="row space-grid">
		    <div class="col-lg-12">
	            <?= BsHtml::tabs($this->menu); ?>
		    </div>
	    </div>
	<?php endif; ?>
	<?php if ($flashMessages = Yii::app()->user->getFlashes()): ?>    
	    <div class="row">
		    <div class="col-lg-12">
				<?php foreach($flashMessages as $key => $message): 
					echo $this->showFlashMessage($key, $message);
			    endforeach; ?>	
		    </div>
	    </div>
	<?php endif; ?>
    <div class="row">
	    <div class="col-lg-12">
	        <?php echo $content; ?>
	    </div>
    </div>
</div> <!-- /container -->  
<?php $this->endContent(); ?>