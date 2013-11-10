<?php $this->beginContent('/layouts/main'); ?>
<div class="container">
	<div class="span-18">
		<div id="content">
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
	<div class="span-6 last">
		<div id="sidebar">
                    
                        <?php if(!Yii::app()->user->isGuest && get_class($this) == 'UserController') $this->widget('UsersMenu'); ?>

		</div><!-- sidebar -->
	</div>
</div>
<?php $this->endContent(); ?>