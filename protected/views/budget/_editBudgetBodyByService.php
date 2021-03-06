<?php 
$settings = new Settings();
?>
    <?php echo $this->renderPartial('_tabBudgetServiceConfig',array(
						'model'=>$model,
						'modelProduct'=>$modelProduct,
						'modelBudgetItem'=>$modelBudgetItem,
						'priceListItemSale'=>$priceListItemSale,
						'modelBudgetItemGeneric'=>$modelBudgetItemGeneric,));
    ?>

	<section id="PRODUCTS"></section>    
    <div class="row contenedorPresu noBorder">
    <div class="col-sm-12">
      <div class="tituloFinalPresu">Equipos por Servicio</div>
      <ul class="nav nav-tabs">
        <?php 
        $first = true;
        
        $criteria = new CDbCriteria();
        $criteria->with[]="service";
        $criteria->addCondition('Id_budget='.$model->Id);
        $criteria->addCondition('version_number='.$model->version_number);
        $criteria->group="Id_service";
        $criteria->order="service.description";
        $services = BudgetItem::model()->findAll($criteria);
        foreach($services as $item)	{ ?>
        <li class="<?php echo ($first?'active':'');?>">
        	<a onclick="changeTabByService(<?php echo (isset($item->Id_service)?$item->Id_service:0);?>, '<?php echo (isset($item->service)?$item->service->description:"General");?>')" href="#itemService_<?php echo (isset($item->Id_service)?$item->Id_service:0);?>" data-toggle="tab">
        		<span id="areaProjectDescription_<?php echo $item->Id?>"><?php echo (isset($item->service)?$item->service->description:"General");?></span>
        	</a>
        </li>
		<?php if($first)
	        {
	        	$first= false;
	        }
		}
		?>
      
        <li class="pull-right">
          <div class="btn-group btnAlternateView">
		  <button onclick="editBudget(<?php echo $model->Id?>,<?php echo $model->version_number?>)" type="button" class="btn btn-default">Áreas</button>
		  <button onclick="editBudgetByService(<?php echo $model->Id?>,<?php echo $model->version_number?>)" type="button" class="btn btn-default active">Servicios</button>
          </div>
        </li>
      </ul>
      <div class="tab-content">
        <?php
        $first = true;
        $idTabService = 0;
        foreach($services as $item)	{ ?>
        <div class="tab-pane <?php echo $first?'active':'';?>" id="itemService_<?php echo (isset($item->Id_service)?$item->Id_service:0);?>">
        <?php 
	        
        	if($first)
        	{
        		$first = false;
        		$idTabService = isset($item->Id_service)?$item->Id_service:0;
        	}
	        $modelBudgetItem->Id_service = $item->Id_service;
	        
	        echo $this->renderPartial('_tabEditBudgetByService',array(
						'model'=>$model,
						'modelProduct'=>$modelProduct,
						'modelBudgetItem'=>$modelBudgetItem,
						'priceListItemSale'=>$priceListItemSale,
						'modelBudgetItemGeneric'=>$modelBudgetItemGeneric,
			));
		?>

   </div>  <!-- /.tab-pane --> 
  		<?php } 
  		echo CHtml::hiddenField("idTabService",$idTabService, array('id'=>'idTabService'));
  		?>
  		
  		
  		   </div>  <!-- /.tab-content --> 
    </div>
    <!-- /.col-sm-12 --> 
  </div>
  <!-- /.row --> 
  
<div class="row contenedorPresu">
    <div class="col-sm-12">
		<div class="tituloFinalPresu">Adicionales - <span id="additional-description">General</span></div>
		<a data-toggle="tab" href="#" id="additional-toggle"></a>
		<div class="tab-content">
			<?php 
			$first = true;
			foreach($services as $item)	{?>
			<div class="tab-pane <?php echo $first?'active':'';?>" id="additional_<?php echo (isset($item->Id_service)?$item->Id_service:0);?>">
			<?php 
				if($first)
					$first = false;
				$modelBudgetItem->Id_service = $item->Id_service;
				$this->renderPartial('_tabEditBudgetServiceExtras',array(
										'model'=>$model,
										'modelBudgetItem'=>$modelBudgetItem,
										));
			?>
			</div>
		<?php } ?>
		</div>
    </div>
    <!-- /.col-sm-12 --> 
</div>
<!-- /.row --> 

    <?php echo $this->renderPartial('_tabEditBudgetTotals',array(
						'model'=>$model,
						'modelProduct'=>$modelProduct,
						'modelBudgetItem'=>$modelBudgetItem,
						'priceListItemSale'=>$priceListItemSale,
						'modelBudgetItemGeneric'=>$modelBudgetItemGeneric,));
    ?>
    
  <div class="row navbar-fixed-bottom">
    <div class="col-sm-12">
      <div id="statusSaving" class="statusFloatSaving" style="display:none">
        <i class="fa fa-spinner fa-spin fa-fw"></i> Guardando
      </div>
      <div id="statusSaved" class="statusFloatSaved" style="display:none">
        <i class="fa fa-check fa-fw"></i> Guardado
        </div>
    </div>
    <!-- /.col-sm-12 --> 
  </div>
  <!-- /.row --> 
  
  
<script type="text/javascript">
function downItemToBottom(id,grid)
{
	statusStartSaving();
	
	$.post(
			'<?php echo BudgetController::createUrl('AjaxDownToBottomBudgetItem')?>',
			 {
			 	id: id,
			 },'json').success(
				function(data)				 
				{ 
					statusSaved();
					$.fn.yiiGridView.update(grid);											
				}
			).error(function(){statusSavedError();});			
}
    
function downItem(id,grid)
{
	statusStartSaving();
	
	$.post(
			'<?php echo BudgetController::createUrl('AjaxDownBudgetItem')?>',
			 {
			 	id: id,
			 },'json').success(
				function(data)				 
				{ 
					statusSaved();
					$.fn.yiiGridView.update(grid);											
				}
			).error(function(){statusSavedError();});			
}
function upItemToAbove(id,grid)
{
	statusStartSaving();
	
	$.post(
			'<?php echo BudgetController::createUrl('AjaxUpToAboveBudgetItem')?>',
			 {
			 	id: id,
			 },'json').success(
				function(data)				 
				{ 
					statusSaved();
					$.fn.yiiGridView.update(grid);											
				}
			).error(function(){statusSavedError();});			
}
    
function upItem(id,grid)
{
	statusStartSaving();
	$.post(
			'<?php echo BudgetController::createUrl('AjaxUpBudgetItem')?>',
			 {
			 	id: id,
			 },'json').success(
				function(data)				 
				{ 
					statusSaved();
					$.fn.yiiGridView.update(grid);											
				}
			).error(function(){statusSavedError();});			
}

function editAreaProject()
{
	idAreaProject = $("#idTabAreaProject").val();
	$.post(
			'<?php echo BudgetController::createUrl('area/AjaxShowUpdateModalAreaProject')?>',
			 {
			 	id: idAreaProject,
			 	field_caller:'areaProjectDescription_'+idAreaProject
			 },'json').success(
				function(data) 
				{ 
					if(data!='')
					{
						$('#modalPlaceHolder').html(data);
						$('#modalPlaceHolder').modal('show');					
					}
				}
			).error(function(){});			
}
    
function updateGridExtras()
{
	$.fn.yiiGridView.update('budget-item-additional-grid_' + $("#idTabService").val());
	$.fn.yiiGridView.update('totals-services-grid');	
}
    
    function statusStartSaving()
        {
        	$("#statusSaved").hide();
        	$("#statusSaving").fadeIn();				        	
		}
        function statusSaved()
        {
			$("#statusSaving").fadeOut(function(){$("#statusSaved").fadeIn();});							
        }
		function statusSavedError()
		{
			$("#statusSaving").fadeOut();				
		}
function setTotals()
{
	$.post(
		'<?php echo BudgetController::createUrl('AjaxGetTotals');?>',
		 {
		 	Id: <?php echo $model->Id;?>,
			version_number:<?php echo $model->version_number;?>,
		 },'json').success(
			function(data) 
			{ 
				if(data!='')
				{
					var response = jQuery.parseJSON(data);							
					$('#totals_price_w_discount').html(response.total_price_with_discount);					
					$('#totals_discount').html(response.total_discount);
					$('#totals_total_price').html(response.total_price);
					$.fn.yiiGridView.update('totals-services-grid');
				}
			}
		);		
}

$('#totals_percent_discount').keyup(function(){
	validateNumberInteger($('#totals_percent_discount'));
	if($('#totals_percent_discount').val()>100)	$('#totals_percent_discount').val(100);
});							
$('#totals_percent_discount').change(
	function(){
		statusStartSaving();
		$.post(
			'<?php echo BudgetController::createUrl('AjaxUpdatePercentDiscount')?>',
			 {
			 	Id: <?php echo $model->Id?>,
				version_number:<?php echo $model->version_number?>,
				percent_discount: $(this).val(),
			 },'json').success(
				 	function(data) 
				 		{ 
						statusSaved();
				 		if(data!='')
							{
								var response = jQuery.parseJSON(data);							
								$('#totals_price_w_discount').html(response.total_price_with_discount);					
								$('#totals_discount').html(response.total_discount);
								$('#totals_total_price').html(response.total_price);
							}
						}).error(function(){statusSavedError();});
	
	}
	);
    
function changeService(id, object)
	{
	statusStartSaving();
	$.post(
			"<?php echo BudgetController::createUrl('AjaxSaveService')?>",
			{
				Id_budget_item: id,Id_service:$(object).val()
			}
			).success(function(data)					
			{
				$.fn.yiiGridView.update('totals-services-grid');
				statusSaved();
			}).error(function(data)
			{
				statusSavedError();
			},"json");	
}

function changeDiscount(id, object)
{
	statusStartSaving();	
	validateNumber(object);
	$.post(
			"<?php echo BudgetController::createUrl('AjaxSaveDiscountValue')?>",
			{
				Id_budget_item: id,discount:$(object).val()
			}
			).success(function(data)
			{
				statusSaved();
				var response = jQuery.parseJSON(data);
				$("#total_price_"+id).html("<div class=\"usd\"><?php echo $settings->getEscapedCurrencyShortDescription()?></div> "+response.total_price);
				$(object).val(response.discount);
				setTotals();
		}).error(function(data)
			{
			statusSavedError();				
		},"json");	
}
function changeQuantity(id, object)
{
	statusStartSaving();	
	validateNumber(object);
	$.post(
			"<?php echo BudgetController::createUrl('AjaxSaveQuantity')?>",
			{
				Id_budget_item: id,quantity:$(object).val()
			}
			).success(function(data)
			{
				statusSaved();
				var response = jQuery.parseJSON(data);
				$("#total_price_"+id).html(response.total_price+" <div class=\"usd\"><?php echo $settings->getEscapedCurrencyShortDescription()?></div>");
				$(object).val(response.quantity);
				setTotals();
				updateGridExtras();
				//alert("success");				
		}).error(function(data)
			{
			statusSavedError();				
		},"json");	
}
function changeDiscountType(id, object)
{
	statusStartSaving();	
	$.post(
			"<?php echo BudgetController::createUrl('AjaxSaveDiscountType')?>",
			{
				Id_budget_item: id,discount_type:$(object).val()
			}
			).success(function(data)
			{
				statusSaved();
				var response = jQuery.parseJSON(data);
				$("#total_price_"+id).html("<div class=\"usd\"><?php echo $settings->getEscapedCurrencyShortDescription()?></div> "+response.total_price);
				setTotals();
		}).error(function(data)
			{
			statusSavedError();				
		},"json");	
}

function deleteBudgetItemByService(id,idService)
{
	if(confirm("¿Esta seguro que desea eliminar este ítem?"))
	{
		statusStartSaving();
			$.post(
				'<?php echo BudgetController::createUrl('AjaxDeleteBudgetItem')?>',
				 {
				 	id: id,
				 },'json').success(						 
					function(data) 
					{
						 statusSaved();
						 $.fn.yiiGridView.update('budget-item-grid_'+idService);
						 updateGridExtras(); 
					}
				).error(function(){statusSavedError();});		
	}	
 	return false;
}
function deleteBudgetItem(id,idAreaProject,idArea)
{
	if(confirm("¿Esta seguro que desea eliminar este ítem?"))
	{
		statusStartSaving();
			$.post(
				'<?php echo BudgetController::createUrl('AjaxDeleteBudgetItem')?>',
				 {
				 	id: id,
				 },'json').success(						 
					function(data) 
					{
						 statusSaved();
						 $.fn.yiiGridView.update('budget-item-grid_'+idAreaProject+"_"+idArea);
						 updateGridExtras(); 
					}
				).error(function(){statusSavedError();});		
	}	
 	return false;
}

function fillAndOpenDD(id)
{
	$(".precioTabla").removeClass("open");
	$.post(
			'<?php echo BudgetController::createUrl('ajaxFillDDPriceSelector')?>',
			 {
			 	Id: id,
			 },'json').success(
				function(data) 
				{ 
					if(data!='')
					{
						$("#btn_price_"+id).parent().addClass("open");
						$("#ul_price_"+id).html(data);
					}
				}
			).error(function(){});		
	
 	return false;
}
$("#addAreaToProject").click(function()
{	
	$.post(
			'<?php echo BudgetController::createUrl('ajaxFillAddAreaToProject')?>',
			 {
			 	Id_project: <?php echo $model->Id_project?>
			 },'json').success(
				function(data) 
				{ 
					if(data!='')
					{
						$('#modalPlaceHolder').html(data);
						$('#modalPlaceHolder').modal('show');					
					}
				}
			).error(function(){});		
})
</script>
