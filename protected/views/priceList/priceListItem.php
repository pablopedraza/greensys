<?php
$this->breadcrumbs=array(
	'Price List'=>array('index'),
	'Assign Product',
);

$this->menu=array(
	array('label'=>'List PriceList', 'url'=>array('index')),
	array('label'=>'Create PriceList', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){

	$.fn.yiiGridView.update('price-list-item-grid', {
		data: $(this).serialize()
	});
	return false;
});



				
jQuery('#price-list-item-grid a.delete').live('click',function() {
        if(!confirm('Are you sure you want to pay for this item?')) return false;
        
        var url = $(this).attr('href');
        
        //  do your post request here
        $.post(url,function(res){

         }).success(function() {
         	$.post(
				'".PriceListController::createUrl('AjaxFillPriceListItemGrid')."',
			{
			IdPriceList: $('#PriceList_Id :selected').attr('value')},
			function(data) {
				$('#PriceListItems').html(data);
				$('#PriceListItems').find('input.txtCost').each(
							function(index, item){
						
								$(item).keyup(function(){
							        validateNumber($(this));
		
								});
								
								
								
								$(item).change(function(){
														var target = $(this);
														$.post(
															'".PriceListController::createUrl('AjaxUpdateCost')."',
															 {
															 	idPriceListItem:ddlProductId = $(this).attr('id'),
																cost:$(this).val()
															}).success(
																 	function() 
																 		{ 
																 			$(target).parent().parent().find('#saveok').animate({opacity: 'show'},4000);
																			$(target).parent().parent().find('#saveok').animate({opacity: 'hide'},4000); 
																		});
															
														});
								
								
								
							}
						);
			}
            )})

        return false;
});

");


	
	
	
	
	
?>
<script type="text/javascript">
function validateNumber(obj)
{
	var value=$(obj).val();
    var orignalValue=value;
    value=value.replace(/[0-9]*/g, "");			
   	var msg="Only Decimal Values allowed."; 						
   	value=value.replace(/\./, "");

    if (value!=""){
    	orignalValue=orignalValue.replace(value, "");
    	$(obj).val(orignalValue);
    	alert(msg);
    }
}
</script>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'priceList-form',
		'enableAjaxValidation'=>true,
));
		$modelPriceListItem = PriceListItem::model();
		$model= PriceList::model();
		$priceListDB= PriceList::model()->findAll();
		?>
		<div id="category" style="width:100%;float:left;">
		<?php $modelCategory = Category::model(); ?>
		<?php echo $form->labelEx($modelCategory,'Category'); ?>
		<?php echo $form->dropDownList($modelCategory, 'Id', CHtml::listData($modelCategory->findAll(), 'Id', 'description'),		
			array(
				'ajax' => array(
				'type'=>'POST',
				'url'=>PriceListController::createUrl('AjaxFillProducts'),
				'update'=>'#product', //selector to update
				'success'=>'js:function(data)
				{
					if($("#Category_Id :selected").attr("value")=="")
					{
						$("#product").html(data);
						$( "#product" ).animate({opacity: "hide"},"slow");
						$( "#dropContent" ).animate({opacity: "hide"},"slow");
					}
					else
					{
						$("#product").html(data);
						$( "#product" ).animate({opacity: "show"},"slow");
						$( "#product" ).children().children().each(
						function(index, item){
							$(item).draggable({"helper":"clone","connectToSortable":"#ddlAssigment"});
						}
						);
						$( "#dropContent" ).animate({opacity: "show"},"slow");
					}
				}',
				//leave out the data key to pass all form values through
				),'prompt'=>'Select a Category'
			)		
		);
		?>
		</div>
		<div id="product" class="selectable-items"style="width:50%;float:left;display: none">
		<?php
		$this->widget('ext.draglist.draglist', array(
					'id'=>'dlProduct',
					'items' => array(),
					'options'=>array(
							//'scope'=>'products',
							'helper'=> 'clone',
		),
		));
		?>
		</div>
		<div id="dropContent" style="width:50%;float:left;display: none">
			<?php  
			$this->beginWidget('zii.widgets.jui.CJuiDroppable', array(
				// additional javascript options for the droppable plugin
				'id'=>'droppable',
				'options'=>array(
				//'scope'=>'products',
				'activeClass'=> "ui-state-hover",
				'drop'=> 'js:function( event, ui ) {
					//id = $(ui.ui.draggable).attr("id");
					$.post(
						"'.PriceListController::createUrl('AjaxCreateDialog').'",
						 {
						 	IdPriceList: $("#PriceList_Id :selected").attr("value"),
							IdProduct:$(ui.draggable).attr("id")
						 },
						 function(data) {
  							$("#priceListDialogDiv").html(data);
						}); 
					$("#priceListItemDialog").dialog("open");			
				}'
			
				),
				'htmlOptions'=>array('style'=> 'width: 50%; height: 250px; padding: 0.5em; float: rigth; margin: 20px;'),
			));
			echo 'Drop here to assign';			 	
			$this->endWidget();
			?>
		</div>				
		<div id="priceList" style="width:60%;float:left">
		
	<?php	$priceLists = CHtml::listData($priceListDB, 'Id', 'date_validity');?>

	<?php echo $form->labelEx($model,'Price List'); ?>

		<?php echo $form->dropDownList($model, 'Id', $priceLists,		
			array(
				'ajax' => array(
				'type'=>'POST',
				'url'=>PriceListController::createUrl('AjaxFillPriceListItemGrid'),
				'success'=>'js:function(data)
				{
					if($("#Product_Id :selected").attr("value")=="")
					{
						$("#PriceListItems").html(data);
					}
					else
					{
						$("#PriceListItems").html(data);						
						$("#PriceListItems").find("input.txtCost").each(
							function(index, item){
						
								$(item).keyup(function(){
							        validateNumber($(this));
		
								});
								
								
								
								$(item).change(function(){
														var target = $(this);
														$.post(
															"'.PriceListController::createUrl('AjaxUpdateCost').'",
															 {
															 	idPriceListItem:ddlProductId = $(this).attr("id"),
																cost:$(this).val()
															}).success(
																 	function() 
																 		{ 
																 			$(target).parent().parent().find("#saveok").animate({opacity: "show"},4000);
																			$(target).parent().parent().find("#saveok").animate({opacity: "hide"},4000); 
																		});
															
														});
								
								
								
							}
						);
					}
				}',
				//leave out the data key to pass all form values through
				),'prompt'=>'Select a Price List'
			)		
		);
		?>
		</div>
<div id="PriceListItems"  style="width:100%;float:left">
</div>
	<?php $this->endWidget(); ?>

</div><!-- form -->

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array( // the dialog
    'id'=>'priceListItemDialog',
    'options'=>array(
        'title'=>'Assign Product',
        'autoOpen'=>false,
        'modal'=>true,
        'width'=>250,
        'height'=>250,
        'open'=>'js:function(event,ui){}',
		'buttons'=>array('Assing'=>'js:function() {
							$.post(
								"'.PriceListController::createUrl('AjaxAddPriceListItem').'",
								 {
								 	IdProduct:$( "#IdProduct" ).val(),
									IdPriceList:$( "#IdPriceList" ).val(),
									Cost:$( "#Cost" ).val()
								 }).success(
									 	function() 
									 		{
									 		$.post(
									 			"'.PriceListController::createUrl('AjaxFillPriceListItemGrid').'",
									 			{IdPriceList: $("#PriceList_Id :selected").attr("value")},
									 			function(data) {
  													$("#PriceListItems").html(data);
  													$("#PriceListItems").find("input.txtCost").each(
														function(index, item){
													
															$(item).keyup(function(){
														        validateNumber($(this));
															});
															
															$(item).change(function(){
															var target = $(this);
															$.post(
																"'.PriceListController::createUrl('AjaxUpdateCost').'",
																 {
																 	idPriceListItem:ddlProductId = $(this).attr("id"),
																	cost:$(this).val()
																}).success(
																	 	function() 
																	 		{ 
																	 			$(target).parent().parent().find("#saveok").animate({opacity: "show"},4000);
																				$(target).parent().parent().find("#saveok").animate({opacity: "hide"},4000); 
																			});
																
															});
															
															}// end function(index, item)
															
													);//end .each 
								
												}
									 		);
											}
											
											);							
							
							$( this ).dialog( "close" );
					}',
				'Cancel'=>'js:function() {
					$( this ).dialog( "close" );
				}'
			),        
    ),
));?>
<div class="divForForm" id="priceListDialogDiv">
	
</div>
 
<?php $this->endWidget();?>

