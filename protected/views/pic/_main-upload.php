<?php
    $upId = get_class($model) . "-form";
    $authId = 'auth-block';
    $loginId = 'login-form';
    $regId = 'reg-form';
    $butId = 'buttons-block';
?>


<div class="form">
    <div class="row">
        <?php
        $image_types = 'jpeg|jpg|gif|png';

        // transform $image_types string to fit appropriate style
        $glue = 'image/';
        $accept = $glue . implode(',' . $glue, explode('|', $image_types));
        $js_mime = '/^image\/(' . $image_types . ')$/';

        $this->widget('xupload.XUpload', array(
            'id' => $upId,
            'url' => Yii::app()->createUrl("pic/upload"),
            'model' => $model,
            'attribute' => 'file',
            'multiple' => true,
            'autoUpload' => false,
            'formView' => 'main-form',
            'uploadView' => 'main-upload',
            'downloadView' => 'main-download',
            'accept' => $accept,
            'options' => array(
                'maxNumberOfFiles' => 5,
                'maxFileSize' => 3000000,
                'acceptFileTypes' => 'js:' . $js_mime,
                'previewSourceFileTypes' => 'js:' . $js_mime,
                'submit' => "js:function (e, data) {
                                    $('#$authId').dialog('open');
                                    return false;
                                }",
                'stop' => "js:function (e, data) {
                                    document.location.reload(true);
                                }",
                
            )
        ));
        
        Yii::app()->clientScript->registerScript($butId, "
            $('#$upId').attr('data-count', 0);
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
        ");
        ?>
    </div>
</div>

<?php $this->beginWidget('zii.widgets.jui.CJuiDialog',array(
                'id'=>$authId,
                'options'=>array(
                    'title'=>'Authorize',
                    'autoOpen'=>false,
                    'modal'=>'true',
                    'width'=>'700',
                    'height'=>'auto',
                ),
                ));
                ?>
    <div class="<?php echo $loginId;?> form">
        <h3>Login</h3>

        <p>Please fill out the following form with your login credentials:</p>

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => $loginId,
            'enableAjaxValidation' => true,
            'action' => Yii::app()->createUrl('user/login'),
        ));
        ?>

        <p class="note">Fields with <span class="required">*</span> are required.</p>
        
        <!--To remove autofocus on the first element-->
        <span class="ui-helper-hidden-accessible"><input type="text"/></span>
        
        <div class="row">
            <?php echo $form->labelEx($modelLogin, 'username'); ?>
            <?php echo $form->textField($modelLogin, 'username', array('autocomplete' => 'off')); ?>
            <?php echo $form->error($modelLogin, 'username'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($modelLogin, 'password'); ?>
            <?php echo $form->passwordField($modelLogin, 'password', array('autocomplete' => 'off')); ?>
            <?php echo $form->error($modelLogin, 'password'); ?>
        </div>

        <div class="row rememberMe">
            <?php echo $form->checkBox($modelLogin, 'rememberMe'); ?>
            <?php echo $form->label($modelLogin, 'rememberMe'); ?>
            <?php echo $form->error($modelLogin, 'rememberMe'); ?>
        </div>

         <div class="row submit">
        <?php echo CHtml::ajaxSubmitButton('Login',Yii::app()->createUrl('user/login', array('ajax'=>'1')),array(
            'success'=>"js: function(data) {
                        $('#$upId').fileupload({submit: function (e, data) {
                            data.formData = {ajax: 1};
                            return true;
                        }});
                        $('.start').click();
                        $('#$authId').dialog('close');
                        $('#$upId').prepend('<div class=\'loading-block\'></div>').css({'position': 'relative', 'min-height': '200px'});
                    }",
            'beforeSend' => "function(){
                        $('#$authId').prepend('<div class=\'loading-block\'></div>').css({'position': 'relative', 'min-height': '200px'});}",
            'complete' => "function(){
                        $('#$authId .loading-block').remove();}",
            
        )); ?>
    </div>

        <?php $this->endWidget(); ?>
    </div>


    <div class="<?php echo $regId;?> form">
        <h3>Registration</h3>

        <p>Please fill out the following form with your login credentials:</p>

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => $regId,
            'enableAjaxValidation' => true,
            'action' => Yii::app()->createUrl('user/reg'),
        ));
        ?>

        <p class="note">Fields with <span class="required">*</span> are required.</p>

        <?php echo $form->errorSummary($modelReg); ?>

        <div class="row">
            <?php echo $form->labelEx($modelReg, 'username'); ?>
            <?php echo $form->textField($modelReg, 'username', array('maxlength' => 128, 'autocomplete' => 'off')); ?>
            <?php echo $form->error($modelReg, 'username'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($modelReg, 'password'); ?>
            <?php echo $form->passwordField($modelReg, 'password', array('maxlength' => 128, 'autocomplete' => 'off')); ?>
            <?php echo $form->error($modelReg, 'password'); ?>
        </div>
        
        <div class="row">
            <?php echo $form->labelEx($modelReg, 'password_repeat'); ?>
            <?php echo $form->passwordField($modelReg, 'password_repeat', array('maxlength' => 128, 'autocomplete' => 'off')); ?>
            <?php echo $form->error($modelReg, 'password_repeat'); ?>
        </div>

        <div class="row submit">
        <?php echo CHtml::ajaxSubmitButton('Sign Up',Yii::app()->createUrl('user/reg', array('ajax'=>'1')),array(
            'success'=>"js: function(data) {
                        $('#$upId').fileupload({submit: function (e, data) {
                            data.formData = {ajax: 1};
                            return true;
                        }});
                        $('.start').click();
                        $('#$authId').dialog('close');
                        $('#$upId').prepend('<div class=\'loading-block\'></div>').css({'position': 'relative', 'min-height': '200px'});
                    }",
            'beforeSend' => "function(){
                        $('#$authId').prepend('<div class=\'loading-block\'></div>').css({'position': 'relative', 'min-height': '200px'});}",
            'complete' => "function(){
                        $('#$authId .loading-block').remove();}",
        )); ?>
        </div>

    <?php $this->endWidget(); ?>
    </div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>