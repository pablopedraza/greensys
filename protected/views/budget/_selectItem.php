
<div id="display">

 <?php	
$this->widget('zii.widgets.jui.CJuiTabs', array(
    'tabs'=>array(
		'All Products' => array('content' => $this->renderPartial("_filteredGrid",
		array("modelProduct"=>$modelProduct,
				"priceListItemSale"=>$priceListItemSale,
				"model"=>$model,
				"idArea"=>$idArea, 
				"type"=>'byAll'),true)),
        'Filtered by Category' => array('content' => $this->renderPartial("_filteredGrid",
		array("modelProduct"=>$modelProduct,
				"priceListItemSale"=>$priceListItemSale,
				"model"=>$model,
				"idArea"=>$idArea, 
				"type"=>'byCat'),true)),
		'Filtered by Group' => array('content' => $this->renderPartial("_filteredGrid",
		array("modelProduct"=>$modelProduct,
				"priceListItemSale"=>$priceListItemSale,
				"model"=>$model,
				"idArea"=>$idArea, 
				"type"=>'byProd'),true)),
),
// additional javascript options for the tabs plugin
    'options'=>array(
        'collapsible'=>true,
),
));
 ?>

	<p class="messageError"><?php
		echo Yii::app()->lc->t('Product has already been added');
		?></p>

	<br>
			<?php 

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'budget-item-grid_'.$idArea,
	'dataProvider'=>$modelBudgetItem->search(),
 	'filter'=>$modelBudgetItem,
	'summaryText'=>'',
	'columns'=>array(
				array(
					'name'=>'product_code',
				    'value'=>'$data->product->code',
				 
				),
				array(
					'name'=>'parent_product_code',
				    'value'=>'$data->budgetItem->product->code',
	
				),
				array(
					'name'=>'product_customer_desc',
				    'value'=>'$data->product->description_customer',
	
				),
				array(
 					'name'=>'product_brand_desc',
				    'value'=>'$data->product->brand->description',
	
				),
				array(
 					'name'=>'product_supplier_name',
				    'value'=>'$data->product->supplier->business_name',

				),
				array(
 					'name'=>'price',
				    'value'=>'$data->price',
					'type'=>'raw',
			        'htmlOptions'=>array('style'=>'text-align: right;'),
				),
				array(
					'class'=>'CButtonColumn',
					'template'=>'{delete}',
					'buttons'=>array
					(
					        'delete' => array
							(
					            'url'=>'Yii::app()->createUrl("budget/AjaxDeleteBudgetItem", array("id"=>$data->Id))',
							),
					),
				),
			),
)); ?>
</div>