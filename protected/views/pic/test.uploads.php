<?php
/* @var $this PicController */
/* @var $model Pic */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
//	'id'=>'pic-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

        <div class="row">
        <?php $this->widget('CMultiFileUpload', array(
                'model' => $model,
                'attribute' => 'images[]',
                'accept' => 'jpeg|jpg|gif|png', // useful for verifying files
            )); ?>
        </div>
        
        <div class="row">
        <?php $this->widget('ext.yii-dropzone-master.EDropzone', array(
            'url' => Yii::app()->createUrl("pic/index"),
            'model' => $model,
            'attribute' => 'images[]',
            'mimeTypes' => array('image/jpeg', 'image/png'),
        ));?>
        </div>
                    
                
	<div class="row buttons">
		<?php echo CHtml::submitButton('Create'); ?>
	</div>

<?php $this->endWidget(); ?>
        
        <div class="row">
                    <?php 
                    $image_types = 'jpeg|jpg|gif|png';
                    
                    // transform $image_types string to appropriate style
                    $glue = 'image/';
                    $accept = $glue.implode(','.$glue,explode('|', $image_types));
                    $js_mime = '/^image\/('.$image_types.')$/';
                    
                    $this->widget('xupload.XUpload', array(
                        'url' => Yii::app()->createUrl("pic/upload"),
                        'model' => $model,
                        'attribute' => 'file',
                        'multiple' => true,
                        'autoUpload' => false,
                        'accept' => $accept,
//                        'accept' => $mime,
                        'options'=>array(
                                'maxNumberOfFiles'=>5,
                                'maxFileSize'=>3000000,
                                'acceptFileTypes'=>'js:'.$js_mime,
                                'previewSourceFileTypes'=>'js:'.$js_mime,
                                'submit' => "js:function (e, data) {
                                    data.formData = {ajax: 1};
                                    return true;
                                }"
                        )
                    ));?>
	</div>

</div><!-- form -->