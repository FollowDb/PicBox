<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>('user-form'),
	'enableAjaxValidation'=>true,
        'action' => Yii::app()->createUrl(($model->isNewRecord ? 'user/create' : 'user/update')),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('maxlength'=>128,'autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('maxlength'=>128,'autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>
        
	<div class="row">
		<?php echo $form->labelEx($model,'password_repeat'); ?>
		<?php echo $form->passwordField($model,'password_repeat',array('maxlength'=>128,'autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'password_repeat'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->