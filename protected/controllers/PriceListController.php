<?php

class PriceListController extends Controller
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
				'actions'=>array('*'),
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
		$model=new PriceList;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PriceList']))
		{
			$model->attributes=$_POST['PriceList'];
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

		if(isset($_POST['PriceList']))
		{
			$model->attributes=$_POST['PriceList'];
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
		$dataProvider=new CActiveDataProvider('PriceList');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new PriceList('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PriceList']))
			$model->attributes=$_GET['PriceList'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	public function actionPriceListItem()
	{
		//$model= PriceList::model()->findAll();
		$dataProvider=new CActiveDataProvider('PriceList');
		
		
		//$modelPriceListItem= PriceListItem::model()->findAll();
		
		$this->render('priceListItem',array(
					'dataProvider'=>$dataProvider,
			//		'modelPriceListItem'=>$modelPriceListItem
		));
		
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=PriceList::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	public function actionAjaxUpdateCost()
	{
		$idPriceListItem = $_POST['idPriceListItem'];
		$cost = $_POST['cost'];
		$priceListItem= PriceListItem::model()->findByPk($idPriceListItem);
		if($priceListItem!= null)
		{
			$priceListItem->attributes = array('cost'=>(double) $cost);
			$priceListItem->save();
		}
		return $priceListItem;
		
	}
	public function actionAjaxFillPriceListItemGrid()
	{
		$criteria=new CDbCriteria;
		
		if(isset($_POST['PriceList']))
			$criteria->compare('Id_price_list',(int) $_POST['PriceList']['Id']);
		elseif (isset($_POST['IdPriceList'])) 
			$criteria->compare('Id_price_list',(int) $_POST['IdPriceList']);
		
		$dataProvider =  new CActiveDataProvider('PriceListItem', array(
					'criteria'=>$criteria,
		));
		$this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'price-list-item-grid',
			'dataProvider'=>$dataProvider,
			//'filter'=>$data,
			'columns'=>array(
				'product.description_customer',
				'priceList.supplier.business_name',
				array(
					'name'=>'cost',
					'value'=>
                                    	'CHtml::textField("txtCost",
												$data->cost,
												array(
														"id"=>$data->Id,
														"class"=>"txtCost",
													)
											)',
							
					'type'=>'raw',
			        'htmlOptions'=>array('width'=>5),
				),
				array(
					'name'=>'',
					'value'=>'CHtml::image("images/save_ok.png","",array("id"=>"saveok", "style"=>"display:none", "width"=>"20px", "height"=>"20px"))',
					'type'=>'raw',
					'htmlOptions'=>array('width'=>20),
				),
				array(
					'class'=>'CButtonColumn',
					'template'=>'{delete}',
					'buttons'=>array
					(
					        'delete' => array
								(
					            'label'=>'Delete',					            
					            'url'=>'Yii::app()->createUrl("priceListItem/Delete", array("id"=>$data->Id))',
								),
					),
					
				),
			),
		)); 

	}

	
	public function actionAjaxFillProducts()
	{
		
		$data=Product::model()->findAll('Id_category=:Id_category',
		array(':Id_category'=>(int) $_POST['Category']['Id']));
	
		$itemsProduct = CHtml::listData($data, 'Id', 'description_customer');
	
		$this->widget('ext.draglist.draglist', array(
							'id'=>'dlProduct',
							'items' => $itemsProduct,
							'options'=>array(
									'helper'=> 'clone',
									'connectToSortable'=>'#ddlAssigment',
		),
		));
	}
	public function actionAjaxCreateDialog()
	{	
		$idProduct = explode("_",$_POST['IdProduct']);
		$product = Product::model()->findByPk($idProduct[1]);
		$idPriceList = $_POST['IdPriceList'];
		$modelPriceListItem = PriceListItem::model();
		echo CHtml::activeLabelEx($modelPriceListItem,'product.description_customer');
		echo CHtml::textField('product_description',$product->description_customer,array('disabled'=>'disabled'));
	
		echo CHtml::activeLabelEx($modelPriceListItem,'cost');
		echo CHtml::textField('Cost','',array('size'=>10,'maxlength'=>10));
		//echo CHtml::error($modelPriceListItem,'cost');
		echo CHtml::hiddenField('IdProduct',$idProduct[1],array());
		echo CHtml::hiddenField('IdPriceList',$idPriceList,array());
	}
	
	public function actionAjaxAddPriceListItem()
	{	
		$idPriceList = $_POST['IdPriceList'];
		$idProduct = $_POST['IdProduct'];
		$cost = $_POST['Cost'];
		if(!empty($idPriceList)&&!empty($idProduct)&&!empty($cost))
		{
			$priceListItemInDb = PriceListItem::model()->findByAttributes(array('Id_price_list'=>(int) $idPriceList,'id_product'=>(int)$idProduct));
			if($priceListItemInDb==null)
			{
				$priceListItem=new PriceListItem();
				$priceListItem->attributes = array('Id_price_list'=>$idPriceList,'id_product'=>$idProduct,'cost'=>$cost);
				$priceListItem->save();
			}
			else
			{
				//error. already in DB
			}
		}
		
	}
	
	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='price-list-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}