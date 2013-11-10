<?php
/* @var $this UserController */
/* @var $model User */

$this->menu=array(
	array('label'=>'Create User', 'url'=>array('create')),
);
?>

<h1>Manage Users</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
        'enablePagination'=>true,
        'enableSorting'=>true,
	'columns'=>array(
		'id',
		'username',
		'password',
		array(
			'class'=>'CButtonColumn',
                        'buttons' => array (
                            'delete' => array (
                                'visible'=>function($row, $data) { 
                                    return ($data->id == '1' ? false : true);
                                },
                            ),
                            'view' => array ('visible'=>function() { return false; } ),
                        )
		),
	),
)); ?>
