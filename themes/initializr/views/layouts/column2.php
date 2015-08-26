<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>

<div class="container">
	<?php if (!empty($this->menu)): ?>
	    <div class="row space-grid">
		    <div class="col-lg-15">
			    <div class="col-md-offset-10">
					<?= BsHtml::buttonDropdown('Operaciones', $this->menu, [
					    'split' => true,
					    'color' => BsHtml::BUTTON_COLOR_INFO
					]);
					?>        
				</div>
		    </div>
	    </div>
	<?php endif; ?>
	<?php if ($flashMessages = Yii::app()->user->getFlashes()): ?>    
	    <div class="row">
		    <div class="col-lg-15">
				<?php foreach($flashMessages as $key => $message): 
					echo $this->showFlashMessage($key, $message);
			    endforeach; ?>	
		    </div>
	    </div>
	<?php else: ?>
		<div id="statusMsg"></div>
	<?php endif; ?>
    <div class="row">
	    <div class="col-lg-15">
	        <?php echo $content; ?>
	    </div>
    </div>
</div> <!-- /container -->  
<?php $this->endContent(); ?>