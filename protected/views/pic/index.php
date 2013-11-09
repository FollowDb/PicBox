<h3>Upload your pictures using the button below</h3>

<div id="upload-block">
<?php $this->renderPartial('_main-upload', array(
        'model' => $model,
        'modelLogin' => $modelLogin,
        'modelReg' => $modelReg,
    )); ?>
</div>