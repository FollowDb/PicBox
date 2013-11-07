<?php 
foreach (Yii::app()->controller->menu as $menu) {
//        if (Yii::app()->user->checkAccess('view')) {
//            echo '<li>'.CHtml::link($menu['label'],$menu['url'],@$menu['linkOptions'],@$menu['visible']).'</li>';
//            print_r($menu['url']);
//            Yii::app()->user->;
//        }
}
$this->widget('zii.widgets.CMenu',array(
    'items'=>Yii::app()->controller->menu
    )
);
?>