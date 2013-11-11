<?php
/* @var $this PicController */
/* @var $model Pic */
/* @var $modelUp XUploadForm */

/* This view presents the page where user can see his pictures, delete them or upload new ones.*/

$upId = get_class($modelUp) . "-form";
$butId = 'buttons-block';
$gridId = 'pic-grid';
$maxNumberOfFiles = 'undefined';
$delId = 'delete-rows';
?>

<h1>Manage Pics</h1>

<div class="form">
        <div class="row">
                <?php
                $image_types = 'jpeg|jpg|gif|png';

                // transform $image_types string to fit appropriate style
                $glue = 'image/';
                $accept = $glue . implode(',' . $glue, explode('|', $image_types));
                $js_mime = '/^image\/(' . $image_types . ')$/';

                // jQuery File Upload
                $this->widget('xupload.XUpload', array(
                        'id' => $upId,
                        'url' => Yii::app()->createUrl("pic/upload"),
                        'model' => $modelUp,
                        'attribute' => 'file',
                        'multiple' => true,
                        'autoUpload' => false,
                        'formView' => 'list-form',
                        'uploadView' => 'list-upload',
                        'downloadView' => 'list-download',
                        'accept' => $accept,
                        'options' => array(
                                'maxNumberOfFiles' => $maxNumberOfFiles,
                                'maxFileSize' => 3000000,
                                'acceptFileTypes' => 'js:' . $js_mime,
                                'previewSourceFileTypes' => 'js:' . $js_mime,
                                'submit' => "js:function (e, data) {
                                                    var sent = parseInt($('#$upId').attr('data-sent'));
                                                    $('#$upId').attr('data-sent', ++sent);
                                                    if (sent>=$maxNumberOfFiles) { $('#$upId').fileupload('disable'); }
                                                    data.formData = {ajax: 1};
                                                    return true;
                                                }",
                                'start' => "js:function (e, data) {
                                                    $('#$upId').prepend('<div class=\'loading-block\'></div>').css({'position': 'relative', 'min-height': '200px'});
                                                    return true;
                                                }",
                                'stop' => "js:function (e, data) {
                                                    $('#$gridId').yiiGridView('update');
                                                    $('#$upId').css({'min-height': '0'});
                                                    $('#$upId .loading-block').remove();
                                                    return true;
                                                }",
                        )
                ));

                // Some scripts to deal with jquery upload
                Yii::app()->clientScript->registerScript($butId, "
                        $('#".get_class($modelUp)."_file').attr('multiple', 'multiple');
                        $('#$upId').attr('data-count', 0);
                        $('#$upId').attr('data-sent', 0);
                        $('#$butId').hide();
                        $('#$upId').bind('fileuploadadd', function (e, data) {
                                var count = parseInt($('#$upId').attr('data-count'));
                                $('#$upId').attr('data-count', ++count);
                                if (count>0) { $('#$butId').show(); }
                        });
                        $('#$upId').bind('fileuploadfail', function (e, data) {
                                var count = parseInt($('#$upId').attr('data-count'));
                                $('#$upId').attr('data-count', --count);
                                if (count<1) { $('#$butId').hide(); }
                        });
                        $('#$upId').bind('fileuploaddone', function (e, data) {
                                var count = parseInt($('#$upId').attr('data-count'));
                                $('#$upId').attr('data-count', --count);
                                if (count<1) { $('#$butId').hide(); }
                                return true;
                        });
                ");
                ?>
        </div>
</div>

<?php foreach (Yii::app()->user->getFlashes() as $flash) : ?>
        <div class="flash-notice">
                <?php echo $flash; ?>
        </div>
<?php endforeach; ?>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php // GridView form is used to show user's pictures with ability to delete them
    $form=$this->beginWidget('CActiveForm', array(
            'id' => $gridId.'-form',
            'action' => Yii::app()->createUrl('pic/delete'),
    )); 

            echo $form->errorSummary($model);

            echo CHtml::ajaxSubmitButton('Delete selected',array('delete','ajax'=>"$gridId"), array(
                    'success'=>"function () { $('#$gridId').yiiGridView('update'); }",
                    'beforeSend'=>"function(){ if($('#$gridId tbody input:checked').length == 0 || !confirm('Are you sure you want to delete these items?')) return false; }",
            )); 
            $this->widget("ext.fancybox.EFancyBox", array(
                    "target"=>".fancyElm",
                    "config"=>array(
                            'maxWidth'    => 800,
                            'maxHeight'   => 600,
                            'fitToView'   => true,
                            'width'       => '70%',
                            'height'      => '70%',
                            'autoSize'    => true,
                            'closeClick'  => false,
                            'openEffect'  => "none",
                            'closeEffect' => "none",
                            'type'        => 'image',
                    ),
            ));
            $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>$gridId,
                    'dataProvider'=>$model->search(),
                    'filter'=>$model,
                    'columns'=>array(
                            array(
                                    'class'=>'CCheckBoxColumn',
                                    'selectableRows' => 2,
                                    'checkBoxHtmlOptions' => array('name' => "$gridId".'[]'),
                            ),
                            array(
                                    'name'=>'filename',
                                    'header'=>'Image',
                                    'filter'=>false,
                                    'type'=>'html',
                                    'value'=> 'CHtml::link(CHtml::tag("img",array("src"=>Yii::app()->createUrl("pic/send", array("id"=>$data->id,"size_folder"=>"thumbs")))),"",array("class"=>"fancyElm", "href"=>Yii::app()->createUrl("pic/send", array("id"=>$data->id,"size_folder"=>"orig"))))',
                                    'headerHtmlOptions'=>array('width'=>'100px'),
                            ),
                            'title',
                            'type',
                            array(
                                    'name'=>'create_time',
                                    'type' => 'date',
                            ),
                    ),
            ));
    $this->endWidget(); 
?>