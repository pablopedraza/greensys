<?php

class BudgetController extends GController
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
	public function actionView($id, $version)
	{
		$modelBudgetItem = new BudgetItem('search');
		$modelBudgetItem->unsetAttributes();  // clear any default values
		if(isset($_GET['BudgetItem']))
		{
			$modelBudgetItem->attributes =$_GET['BudgetItem'];
		}
		
		//seteo el presupuesto y su version
		$modelBudgetItem->Id_budget = $id;
		$modelBudgetItem->version_number = $version;
		
		
		$this->render('view',array(
			'model'=>$this->loadModel($id, $version),
			'modelBudgetItem'=>$modelBudgetItem,
		));
	}
	public function actionViewService($id, $version)
	{
		$modelBudgetItem = new BudgetItem('search');
		$modelBudgetItem->unsetAttributes();  // clear any default values
		if(isset($_GET['BudgetItem']))
		{
			$modelBudgetItem->attributes =$_GET['BudgetItem'];
		}
	
		//seteo el presupuesto y su version
		$modelBudgetItem->Id_budget = $id;
		$modelBudgetItem->version_number = $version;
	
	
		$this->render('viewService',array(
				'model'=>$this->loadModel($id, $version),
				'modelBudgetItem'=>$modelBudgetItem,
		));
	}
	
	public function actionViewVersion($id, $version)
	{
		$modelBudgetItem = new BudgetItem('search');
		$modelBudgetItem->unsetAttributes();  // clear any default values
		if(isset($_GET['BudgetItem']))
		{
			$modelBudgetItem->attributes =$_GET['BudgetItem'];
		}
	
		//seteo el presupuesto y su version
		$modelBudgetItem->Id_budget = $id;
		$modelBudgetItem->version_number = $version;
	
	
		$this->render('viewVersion',array(
				'model'=>$this->loadModel($id, $version),
				'modelBudgetItem'=>$modelBudgetItem,
		));
	}
	
	public function actionAdminAllVersion($id, $version)
	{
		$model=new Budget('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Budget']))
			$model->attributes=$_GET['Budget'];

		$model->Id = $id;
		
		$this->render('adminAllVersion',array(
			'model'=>$model,
			'modelInstance'=>$this->loadModel($id, $version),
			'version'=>$version,
		));
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Budget;

		$modelBudgetState = BudgetState::model()->findAll();
		$criteria = new CDbCriteria;		
		$criteria->order = 't.description ASC';
		$modelProject = Project::model()->findAll($criteria);
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Budget']))
		{
			//Genero el Id
			$model->Id = Budget::model()->count() + 1;
			
			//Solo para la creacion la version 1
			$model->version_number = 1;
			
			$model->attributes=$_POST['Budget'];
			if($model->save())
				$this->redirect(array('addItem', 'id'=>$model->Id, 'version'=>$model->version_number));
		}
		
		$this->render('create',array(
			'model'=>$model,
			'modelBudgetState'=>$modelBudgetState,
			'modelProject'=>$modelProject,
		));
	}

	public function actionAjaxDeleteBudgetItem($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$model = BudgetItem::model()->findByPk($id);
			$transaction = $model->dbConnection->beginTransaction();
			try {
				
				BudgetItem::model()->deleteAllByAttributes(array('Id_budget_item'=>$id));
				$model->deleteByPk($id);
				
				$transaction->commit();
				
				if(!isset($_GET['ajax']))
					$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('addItem'));
				
			} catch (Exception $e) {
				$transaction->rollback();
			}
	
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id, $version)
	{
		$model=$this->loadModel($id, $version);

		$modelBudgetState = BudgetState::model()->findAll();
		$modelProject = Project::model()->findAll();
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Budget']))
		{
			$model->attributes=$_POST['Budget'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->Id, 'version'=>$model->version_number));
		}

		$this->render('update',array(
			'model'=>$model,
			'modelBudgetState'=>$modelBudgetState,
			'modelProject'=>$modelProject,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id, $version)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id, $version)->delete();

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
		$this->render('index');		
	}
	
	public function actionExportToExcel($id,$versionNumber)
	{
		
		GreenHelper::exportBudgetToExcel($id, $versionNumber);
	}
	
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Budget('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Budget']))
			$model->attributes=$_GET['Budget'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionAjaxSelect()
	{
		$modelProduct = new Product('search');
		$modelProduct->unsetAttributes();
	
		if(isset($_GET['Product']))
			$modelProduct->attributes=$_GET['Product'];
	
		$this->render('_filteredGrid',array(
					'modelProduct'=>$modelProduct,
					'idArea'=>1,
		));
	}
	public function actionAjaxSaveService()
	{
		if(isset($_POST['Id_budget_item'])&&isset($_POST['Id_service']))
		{
			$budgetItem = BudgetItem::model()->findByPk($_POST['Id_budget_item']);
			if(isset($budgetItem))
			{
				$budgetItem->Id_service = $_POST['Id_service'];
				$budgetItem->save();				
			}
		}
	}	
	public function actionAddItem($id, $version)
	{
		$model = $this->loadModel($id, $version);
		
		$modelBudgetItemGeneric = new BudgetItem('search');
		$modelBudgetItemGeneric->unsetAttributes();  // clear any default values		
		$modelBudgetItemGeneric->Id_budget = $id;
		$modelBudgetItemGeneric->version_number = $version;
		
		
		$modelProduct = new Product('search');
		$modelProduct->unsetAttributes();
		
		$priceListItemSale = new PriceListItem();
		$priceListItemSale->unsetAttributes();
		
		$areaProjects = AreaProject::model()->findAllByAttributes(array('Id_project'=>$model->Id_project));
		
		$modelBudgetItem = new BudgetItem('search');
		$modelBudgetItem->unsetAttributes();  // clear any default values
		if(isset($_GET['BudgetItem']))
			$modelBudgetItem->attributes=$_GET['BudgetItem'];

		$modelBudgetItem->Id_budget = $id;
		$modelBudgetItem->version_number = $version;
		if(isset($_GET['Product']))
			$modelProduct->attributes=$_GET['Product'];

		if(isset($_GET['PriceListItem'])){
			$priceListItemSale->attributes=$_GET['PriceListItem'];
		}

		if(isset($_GET['ProductSale']['Id'])){
			$priceListItemSale->Id_product=$_GET['ProductSale']['Id'];
		}

		$this->render('addItem',array(
					'model'=>$model,
					'modelProduct'=>$modelProduct,
					'modelBudgetItem'=>$modelBudgetItem,
					'priceListItemSale'=>$priceListItemSale,
					'areaProjects'=>$areaProjects,
					'modelBudgetItemGeneric'=>$modelBudgetItemGeneric,
		));
	}
	
	public function actionAjaxUpdatePercentDiscount()
	{
		if(isset($_POST['Id'])&&isset($_POST['version_number'])&&isset($_POST['percent_discount']))
		{
			$model = Budget::model()->findByPk(array('Id'=>$_POST['Id'],'version_number'=>$_POST['version_number']));
			if(isset($model))
			{
				$model->percent_discount = $_POST['percent_discount'];
				if($model->save())
				{
					$result['total_price_with_discount']=$model->TotalPriceWithDiscount;
					$result['total_discount']=$model->TotalDiscount;
					$result['total_price']=$model->totalPrice;					
					echo json_encode(array_merge($model->attributes,$result)); 
				}				
			}			
		}
	}
	public function actionAjaxGetTotals()
	{
		if(isset($_POST['Id'])&&isset($_POST['version_number']))
		{
			$model = Budget::model()->findByPk(array('Id'=>$_POST['Id'],'version_number'=>$_POST['version_number']));
			if(isset($model))
			{
				$result['total_price_with_discount']=$model->TotalPriceWithDiscount;
				$result['total_discount']=$model->TotalDiscount;
				$result['total_price']=$model->totalPrice;
				echo json_encode(array_merge($model->attributes,$result));
			}
		}
	}
	
	public function actionAjaxUpdateUpdateGenericItem()
	{
		$id = isset($_POST['Id'])?$_POST['Id']:null;
		$quantity = isset($_POST['quantity'])?$_POST['quantity']:0;
		$price = isset($_POST['price'])?$_POST['price']:0;
		
		if(isset($id) && $quantity > 0 && $price > 0)
		{
			$modelBudgetItemDB = BudgetItem::model()->findByPk($id);
			if(isset($modelBudgetItemDB))
			{
				$modelBudgetItemDB->quantity = $quantity;
				$modelBudgetItemDB->price = $price;
				$modelBudgetItemDB->save();
				$result['total_price']=$modelBudgetItemDB->totalPrice;
				echo json_encode(array_merge($modelBudgetItemDB->attributes,$result));						
				
			}
		}
	}
	
	public function actionAjaxCreateBudgetItem()
	{
		$modelBudgetItem = new BudgetItem();
		
		// Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($modelBudgetItem);
		
		if(isset($_POST['BudgetItem']))
		{
			$modelBudgetItem->attributes = $_POST['BudgetItem'];
			try {
				$modelBudgetItem->save();
				echo json_encode($modelBudgetItem->attributes);
			} catch (Exception $e) {
				echo $e->getMessage();
			}
			
		}
	}
	
	public function actionAjaxBudgetItemChildren()
	{
		$modelBudgetItem = new BudgetItem('search');
		$modelBudgetItem->unsetAttributes();  // clear any default values
		
		if(isset($_GET['BudgetItem']['Id_budget_item']))
			$modelBudgetItem->Id_budget_item = $_GET['BudgetItem']['Id_budget_item'];
		
		if(isset($_GET['BudgetItem']['Id'])){
			$model = BudgetItem::model()->findByPk($_GET['BudgetItem']['Id']);
			$modelBudgetItem->Id_budget_item = $model->Id_budget_item;
		}
		
			
		$priceListItemSale = new PriceListItem();
		$priceListItemSale->unsetAttributes();
			
		if(isset($_GET['ProductSale']['Id'])){
			$priceListItemSale->Id_product=$_GET['ProductSale']['Id'];
		}
			
		echo $this->renderPartial('_budgetItemChildren', array('modelBudgetItem'=>$modelBudgetItem,
															    'priceListItemSale'=>$priceListItemSale,
																));
	}
	
	public function actionAjaxBudgetItemChildrenView()
	{
		$modelBudgetItem = new BudgetItem('search');
		$modelBudgetItem->unsetAttributes();  // clear any default values
	
		if(isset($_GET['BudgetItem']['Id_budget_item']))
			$modelBudgetItem->Id_budget_item = $_GET['BudgetItem']['Id_budget_item'];
	
		if(isset($_GET['BudgetItem']['Id'])){
			$model = BudgetItem::model()->findByPk($_GET['BudgetItem']['Id']);
			$modelBudgetItem->Id_budget_item = $model->Id_budget_item;
		}
	
			
		$priceListItemSale = new PriceListItem();
		$priceListItemSale->unsetAttributes();
			
		if(isset($_GET['ProductSale']['Id'])){
			$priceListItemSale->Id_product=$_GET['ProductSale']['Id'];
		}
			
		echo $this->renderPartial('_budgetItemChildrenView', array('modelBudgetItem'=>$modelBudgetItem,
																    'priceListItemSale'=>$priceListItemSale,
		));
	}
	
	public function actionAjaxVerified()
	{
		if(isset($_POST['IdBudgetItem'])&&isset($_POST['isChecked']))
		{
			$model = BudgetItem::model()->findByPk($_POST['IdBudgetItem']);
			if(isset($model))
			{
				$model->do_not_warning = $_POST['isChecked']=="true"?1:0;
				$model->save();
			}
		}
	}
	public function actionAjaxGetParentInfo()
	{
		
		if(isset($_POST['IdBudgetItem']))
		{
			$model = BudgetItem::model()->findByPk($_POST['IdBudgetItem']);
			if(isset($model))
			{
				$arrayParent = array();
				$arrayParent['parent_code'] = $model->product->code;
				$arrayParent['parent_model'] = $model->product->model;
				$arrayParent['parent_part_number'] = $model->product->part_number;
				$arrayParent['parent_customer_desc'] = $model->product->description_customer;
				$arrayParent['parent_brand_desc'] = $model->product->brand->description;
				$arrayParent['parent_supplier_name'] = $model->product->supplier->business_name;
				$arrayParent['parent_price'] = $model->price;
				$arrayParent['id'] = $model->Id;
				$arrayParent['parent_do_not_warning'] = $model->do_not_warning;
				echo json_encode($arrayParent);
			} 
		}
	}
	
	public function actionAjaxGetChildrenTotalPrice()
	{
		if(isset($_POST['IdBudgetItem']))
		{
			$model = BudgetItem::model()->findByPk($_POST['IdBudgetItem']);
			echo $model->ChildrenTotalPrice;
		}	
	}
	
	public function actionAjaxUpdateChildPrice()
	{
		$idProduct = isset($_POST['IdProduct'])?$_POST['IdProduct']:'';
		$idShippingType = isset($_POST['IdShippingType'])?$_POST['IdShippingType']:'';
		$idBudgetItem = isset($_POST['IdBudgetItem'][0])?$_POST['IdBudgetItem'][0]:'';
		$idPriceList = isset($_POST['IdPriceList'])?$_POST['IdPriceList']:'';
		$price = isset($_POST['price'])?$_POST['price']:'';
		
		if(!empty($idPriceList)&&!empty($idProduct)&&!empty($idShippingType)&&!empty($price)&&!empty($idBudgetItem))
		{
			$model = BudgetItem::model()->findByPk($idBudgetItem);
			$model->Id_shipping_type = $idShippingType;
			$model->Id_price_list = $idPriceList;
			$model->price = $price;
			$model->is_included = 1;
			$model->save();
		}
	}
	
	public function actionAjaxAssignFromStock()
	{
	
		$idBudgetItem = isset($_POST['IdBudgetItem'])?$_POST['IdBudgetItem']:'';
		$idProduct = isset($_POST['IdProduct'])?$_POST['IdProduct']:'';
		
		if(!empty($idProduct)&&!empty($idBudgetItem))
		{
			$modelProductItem = ProductItem::model()->findByAttributes(array(
																		'Id_product'=>$idProduct,
																		'Id_budget_item'=>null,
																		'Id_purchase_order_item'=>null,
																		));
			if(isset($modelProductItem))
			{
				$modelProductItem->Id_budget_item = $idBudgetItem;
				$modelProductItem->save();
			}

		}

	}
	
	public function actionAjaxViewAssign()
	{
	
		$idBudgetItem = isset($_POST['IdBudgetItem'])?$_POST['IdBudgetItem']:'';
		$idProduct = isset($_POST['IdProduct'])?$_POST['IdProduct']:'';
		
		$modelProductItem = ProductItem::model()->findByAttributes(array('Id_product'=>$idProduct,'Id_budget_item'=>$idBudgetItem));
		
		echo $this->renderPartial('_viewAssign',array(
					'model'=>$modelProductItem,
		));
	
	}
	
	public function actionAjaxUnAssignStock()
	{
	
		$idBudgetItem = isset($_POST['IdBudgetItem'])?$_POST['IdBudgetItem']:'';
		$idProduct = isset($_POST['IdProduct'])?$_POST['IdProduct']:'';
	
		$modelProductItem = ProductItem::model()->findByAttributes(array('Id_product'=>$idProduct,'Id_budget_item'=>$idBudgetItem));
		$modelProductItem->Id_budget_item = null;
		$modelProductItem->save();
	
	}
	
	public function actionAjaxUpdateQuantity()
	{
		$idBudgetItem = isset($_POST['IdBudgetItem'])?$_POST['IdBudgetItem']:'';
		$quantity = isset($_POST['quantity'])?$_POST['quantity']:'';
	
		if(!empty($quantity)&&!empty($idBudgetItem))
		{
			$model = BudgetItem::model()->findByPk($idBudgetItem);
			$model->quantity = $quantity;			
			$model->save();
		}
	}
	
	public function actionAjaxQuitItem()
	{
		$idBudgetItem = isset($_POST['IdBudgetItem'])?$_POST['IdBudgetItem']:'';
	
		if(!empty($idBudgetItem))
		{
			$model = BudgetItem::model()->findByPk($idBudgetItem);
			$model->price = 0;
			$model->quantity = 1;
			$model->is_included = 0;
			$model->save();
		}
	}
	
	public function actionAjaxNewVersion()
	{

		$id = isset($_POST['id'])?$_POST['id']:'';
		$version = isset($_POST['version'])?$_POST['version']:'';
		$note = isset($_POST['note'])?$_POST['note']:'';
		
		$model = new Budget;
		$transaction = $model->dbConnection->beginTransaction();
		
		try {
			$previousModel = $this->loadModel($id, $version);
			$previousModel->note = $note;
			$previousModel->save();
			
			
			$model->attributes = $previousModel->attributes;
			$model->date_creation = new CDbExpression('NOW()');
			$model->version_number = $model->version_number + 1;
			$model->note = '';
			
			if($model->save())
			{
				$budgetItems = BudgetItem::model()->findAllByAttributes(array('Id_budget'=>$previousModel->Id, 'version_number'=>$previousModel->version_number));
				
				foreach($budgetItems as $item)
				{
					$modelBudgetItem = new BudgetItem;
					$modelBudgetItem->attributes = $item->attributes;
					$modelBudgetItem->version_number = $model->version_number;
					$modelBudgetItem->save();
				}
				
				$transaction->commit();				
			}
		} catch (Exception $e) {
			$transaction->rollback();
		}
		
	}
	public function actionAjaxSaveDiscountType()
	{
		if(isset($_POST['Id_budget_item'])&&isset($_POST['discount_type']))
		{
			$budgetItem = BudgetItem::model()->findByPk($_POST['Id_budget_item']);
			if(isset($budgetItem))
			{
				$budgetItem->discount_type = $_POST['discount_type'];
				if($budgetItem->save())
				{
					$result['total_price']=$budgetItem->totalPrice;
					echo json_encode(array_merge($budgetItem->attributes,$result));						
				}
			}
		}
	}
	public function actionAjaxSaveDiscountValue()
	{
		if(isset($_POST['Id_budget_item'])&&isset($_POST['discount']))
		{
			$budgetItem = BudgetItem::model()->findByPk($_POST['Id_budget_item']);
			if(isset($budgetItem))
			{
				$budgetItem->discount= $_POST['discount'];
				if($budgetItem->save())
				{
					$result['total_price']=$budgetItem->totalPrice;
					echo json_encode(array_merge($budgetItem->attributes,$result));						
				}
			}
		}
	}
	public function actionAjaxAddBudgetItem()
	{
		$idPriceList = isset($_POST['IdPriceList'])?$_POST['IdPriceList']:'';
		$idProduct = isset($_POST['IdProduct'])?$_POST['IdProduct']:'';
		$idShippingType = isset($_POST['IdShippingType'])?$_POST['IdShippingType']:'';
		$idBudget = isset($_POST['IdBudget'])?$_POST['IdBudget']:'';
		$idVersion = isset($_POST['IdVersion'])?$_POST['IdVersion']:'';
		$idArea = isset($_POST['IdArea'])?$_POST['IdArea']:'';
		$idAreaProject = isset($_POST['IdAreaProject'])?$_POST['IdAreaProject']:'';
		
		if(!empty($idPriceList)&&!empty($idProduct)&&!empty($idShippingType)&&!empty($idBudget)&&!empty($idVersion)&&!empty($idArea))
		{
			$modelPriceListItem = PriceListItem::model()->findByAttributes(array('Id_price_list'=>$idPriceList,'Id_product'=>$idProduct));
			
			$modelBudgetItem = new BudgetItem;
			$modelBudgetItem->Id_budget = $idBudget;
			$modelBudgetItem->Id_price_list = $idPriceList;
			$modelBudgetItem->Id_product = $idProduct;
			$modelBudgetItem->Id_shipping_type = $idShippingType;
			$modelBudgetItem->version_number = $idVersion;
			$modelBudgetItem->Id_area = $idArea;
			$modelBudgetItem->Id_area_project = $idAreaProject;
			$modelBudgetItem->price = ($idShippingType==1)?$modelPriceListItem->maritime_cost:$modelPriceListItem->air_cost;
			$modelBudgetItem->save();
			
			$productGroups = ProductGroup::model()->findAllByAttributes(array('Id_product_parent'=>$idProduct));
			foreach($productGroups as $item)
			{
				for($index = 0; $index < $item->quantity; $index++)
				{
					$modelPriceListItem = PriceListItem::model()->findByAttributes(array('Id_price_list'=>$idPriceList,'Id_product'=>$item->Id_product_child));
					
					$modelBudgetItemChild = new BudgetItem;
					$modelBudgetItemChild->Id_budget = $idBudget;
					$modelBudgetItemChild->Id_product = $item->Id_product_child;
					$modelBudgetItemChild->version_number = $idVersion;
					$modelBudgetItemChild->Id_budget_item = $modelBudgetItem->Id;
					$modelBudgetItemChild->Id_area = $idArea;
					$modelBudgetItemChild->Id_area_project = $idAreaProject;
					$modelBudgetItemChild->price = 0;
					$modelBudgetItemChild->save();
				}
			}
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id, $version)
	{
		$model=Budget::model()->findByPk(array('Id'=>$id,'version_number'=>$version));
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='budget-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
