<?php

class PicController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('upload','list','delete','send'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

        
        public function actions()
        {
                $dir = '/imgs/'.Yii::app()->params['pics_dir'].'/'. Yii::app()->user->name;
                return array(
                        'upload'=>array(
                                'class'=>'xupload.actions.XUploadAction',
                                'path' =>Yii::app() -> getBasePath() . "/..".$dir,
                                'publicPath' => Yii::app() -> getBaseUrl() . $dir,
                                'subfolderVar' => false,
                                'secureFileNames' => true,
                        ),
                );
        }
        
        
        public function actionIndex() 
        {
                if (!Yii::app()->user->isGuest)
                        $this->redirect (array('list'));
                else {
                        Yii::import("xupload.models.XUploadForm");
                        $model = new XUploadForm;
                        $modelLogin = new LoginForm;
                        $modelReg = new User;
                        $this -> render('index', array(
                                'model' => $model,
                                'modelLogin' => $modelLogin,
                                'modelReg' => $modelReg,
                        ));
                }
        }

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id=NULL)
	{
                // Transform data into array, so that we could proccess both array and single values
                $ids = array();
                
                // Check if we received an array of ids
                if(isset($_POST['pic-grid']))
                        $ids = $_POST['pic-grid'];
                
                // Add to array if $id is a single value
                if ($id !== NULL)
                        $ids[]=$id;
                
                foreach($ids as $i)
                {
                        $model = $this->loadModel($i);
                        if ($model->author_id != Yii::app()->user->id)
                                throw new CHttpException(403, 'You are not authorized to perform this action.');
                        
                        // Deleting pitures from db and file system
                        if (!$this->deleteImg($model->filename))
                                Yii::log("$model->filename :Hasn't been deleted!");
                        $model->delete();
                                
                }

		// if AJAX request (triggered by deletion via list grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(array('list'));
	}
        
        protected function deleteImg($filename){
                $path = Yii::getPathOfAlias('webroot').'/imgs/'.Yii::app()->params['pics_dir'].'/'. Yii::app()->user->name.'/';
                $success_orig = false;
                $success_thumb = false;
                if (file_exists($path.'orig/'.$filename))
                        $success_orig = unlink($path.'orig/'.$filename);
                if (file_exists($path.'thumbs/'.$filename))
                        $success_thumb = unlink($path.'thumbs/'.$filename);
                return $success_orig & $success_thumb;
        }

        /**
	 * Lists all user's pictures.
	 */
	public function actionList()
	{
		$model=new Pic('search');
                Yii::import("xupload.models.XUploadForm");
                $modelUp = new XUploadForm;
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Pic']))
			$model->attributes=$_GET['Pic'];

		$this->render('list',array(
			'model'=>$model,
                        'modelUp'=>$modelUp,
		));
	}
        
	/**
	 * Function to stream user's pictures with a check by user's id. 
         * We show only those pictures which belong to the current user.
	 */
	public function actionSend($id,$size_folder)
	{
                $model = $this->loadModel($id);
                if ($model->author_id == Yii::app()->user->id) {
                        $file = '/imgs/'.Yii::app()->params['pics_dir'].'/'. Yii::app()->user->name.'/'.$size_folder.'/'.$model->filename;
                        if (file_exists(Yii::getPathOfAlias('webroot').$file)) {
                                header('X-Accel-Redirect: ' . $file);
                                header('Content-Type: '.$model->type);
                                exit;
                        }
                }
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Pic the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Pic::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Pic $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='pic-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
