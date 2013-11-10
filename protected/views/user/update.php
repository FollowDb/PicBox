<?php
/* @var $this UserController */
/* @var $model User */

$this->menu=array(
	array('label'=>'Create User', 'url'=>array('create'), 'visible'=>Yii::app()->user->id == 1),
	array('label'=>'Manage User', 'url'=>array('admin'), 'visible'=>Yii::app()->user->id == 1),
);
?>

<h1>Update User <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>