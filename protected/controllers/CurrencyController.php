<?php

class CurrencyController extends GController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
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
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Currency;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Currency']))
		{
			$model->attributes=$_POST['Currency'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->Id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Currency']))
		{
			$model->attributes=$_POST['Currency'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->Id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->redirect(array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Currency('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Currency']))
			$model->attributes=$_GET['Currency'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionAjaxShowCreateModal()
	{
		$model = new Currency();
		$field_caller ="";
		
		if($_POST['field_caller'])
			$field_caller=$_POST['field_caller'];
		
		$this->renderPartial('_formModal',array(
				'model'=>$model,
				'field_caller'=>$field_caller
		));
	}
	
	public function actionAjaxCreateCurrencyConversor()	
	{
		$model = new CurrencyConversor();
		
		if(isset($_POST['CurrencyConversor']))
		{
			$model->attributes=$_POST['CurrencyConversor'];
			if($model->save())
				echo json_encode($model->attributes);
		}
		
	}
	public function actionAjaxShowCreateModalCurrencyConversor()
	{
	
		if($_POST['idCurrencyFrom'])
		{
			$field_caller ="";
			$model = new CurrencyConversor();
			$model->Id_currency_from=$_POST['idCurrencyFrom'];
			$model->validity_date = Yii::app()->dateFormatter->formatDateTime(time(),'small',null);
			$model->factor = "0.0000";
				
			$modelCurrencyFrom=$this->loadModel($_POST['idCurrencyFrom']);
			
			
			if($_POST['field_caller'])
				$field_caller=$_POST['field_caller'];
			$criteria=new CDbCriteria;
			$criteria->addCondition('Id!='.$modelCurrencyFrom->Id,'OR');
			$criteria->addCondition('Id not in (select Id_currency_to from currency_conversor where Id_currency_from = '.$modelCurrencyFrom->Id.')');
			$criteria->order="description";
			$ddlCurrency = Currency::model()->findAll($criteria);
			
			$this->renderPartial('_formModalCurrencyConversor',array(
					'model'=>$model,
					'modelCurrencyFrom'=>$modelCurrencyFrom,
					'ddlCurrency'=>$ddlCurrency,
					'field_caller'=>$field_caller
			)/*parametros extras para que funcione CJuiDatePicker*/,false, true);				
		}
	}
	
	public function actionAjaxShowUpdateModal()
	{
		if(isset($_POST['id']))
		{
			$model=$this->loadModel($_POST['id']);
			$field_caller ="";
			if($_POST['field_caller'])
				$field_caller=$_POST['field_caller'];
			// Uncomment the following line if AJAX validation is needed
			$this->renderPartial('_formModal',array(
					'model'=>$model,
					'field_caller'=>$field_caller
			));
		}
	}
	public function actionAjaxShowUpdateModalCurrencyConversor()
	{
		if(isset($_POST['id']))
		{
			$model=CurrencyConversor::model()->findByAttributes(array('Id'=>$_POST['id']));
			$field_caller ="";
			if($_POST['field_caller'])
				$field_caller=$_POST['field_caller'];

			$criteria=new CDbCriteria;
			$criteria->addCondition('Id!='.$model->currencyFrom->Id);
			
			$criteria->order="description";
			$ddlCurrency = Currency::model()->findAll($criteria);
			$this->renderPartial('_formModalCurrencyConversor',array(
					'model'=>$model,
					'modelCurrencyFrom'=>$model->currencyFrom,
					'ddlCurrency'=>$ddlCurrency,
					'field_caller'=>$field_caller
			)/*parametros extras para que funcione CJuiDatePicker*/,false, true);
		}
	}
	
	public function actionAjaxUpdateCurrencyConversor()
	{
		if(isset($_POST['CurrencyConversor']['Id']))
		{
			$model =CurrencyConversor::model()->findByAttributes(array('Id'=>$_POST['CurrencyConversor']['Id']));

			if(isset($_POST['CurrencyConversor']))
			{
				$model->attributes=$_POST['CurrencyConversor'];
				$model->Id = null;
				$model->setIsNewRecord(true);
				if($model->save())
					echo json_encode($model->attributes);
			}
		}
	}
	
	public function actionAjaxCreate()
	{
		$model = new Currency();
	
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);
	
		if(isset($_POST['Currency']))
		{
			$model->attributes=$_POST['Currency'];
			if($model->save())
				echo json_encode($model->attributes);
		}
	}
	
	public function actionAjaxUpdate()
	{
		if(isset($_POST['Currency']['Id']))
		{
			$model=$this->loadModel($_POST['Currency']['Id']);
	
			if(isset($_POST['Currency']))
			{
				$model->attributes=$_POST['Currency'];
				if($model->save())
					echo json_encode($model->attributes);
			}
		}
	}
	
	public function actionAjaxDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$id = $_POST['id'];
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();
	
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	public function actionAjaxDeleteCurrencyConversor()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$id = $_POST['id'];
			// we only allow deletion via POST request
			CurrencyConversor::model()->deleteAllByAttributes(array('Id'=>$_POST['id']));
	
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}	
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Currency::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='currency-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
