<script type="text/javascript">

function downloadPDF(id, version)
{
	var params = "&id="+id+"&version="+version;
	window.open("<?php echo BudgetController::createUrl('DownloadPDF'); ?>" + params, "_blank");
	return false;	
}

function changeCurrencyView(obj, id, version)
{
	statusStartSaving();	
	$.post("<?php echo BudgetController::createUrl('AjaxChangeCurrencyView'); ?>",
			{
				id:id,
				version:version,
				idCurrencyView:obj.value				
			}
		).success(
			function(data){
				statusSaved();
			}).error(function(){statusSavedError();});
		return false;
}

function closeVersion(id, version)
{
	if (confirm('¿Desea cerrar esta versión y enviarla a "Esperando Respuesta"?')) 
	{
		$.post("<?php echo BudgetController::createUrl('AjaxCloseVersion'); ?>",
			{
				id:id,
				version:version
			}
		).success(
			function(data){
				window.location = "<?php echo BudgetController::createUrl("index")?>";
				return false;
			});
		return false;
	}
	return false;	
}

function openUpdateBudget(idBudget, version)
{
	$.post("<?php echo BudgetController::createUrl('AjaxOpenUpdateBudget'); ?>",
			{
				idBudget:idBudget,
				version:version
			}
		).success(
			function(data){
				$('#myModalFormBudget').html(data);
		   		$('#myModalFormBudget').modal('show');	  
			});
		return false;
}

function changeTabByService(idService)
{
	return true;	
}
function changeTab(idArea,idAreaProject)
{
	$('#idTabArea').val(idArea);
	$('#idTabAreaProject').val(idAreaProject);
}

function addQty(idProduct)
{
	var qty = $("#qty-field-"+idProduct).val();
	var idAreaProject = $('#idTabAreaProject').val();
	var idArea = $('#idTabArea').val();
	var idBudget = $('#idBudget').val();
	var version = $('#version').val();
	$.post("<?php echo BudgetController::createUrl('AjaxAddProduct'); ?>",
			{
				idBudget:idBudget,
				version:version,
				idProduct:idProduct,
				idArea:idArea,
				idAreaProject:idAreaProject,
				qty: qty
			}
		).success(
			function(data){
				$.fn.yiiGridView.update('product-grid-add', {
					data: $(this).serialize() + '&idArea=' + $('#idTabArea').val()+'&idAreaProject=' + $('#idTabAreaProject').val()
				});			
				
				$('#total-qty').children().text(data); 
			});
		return false;
}

function addProduct(id,version)
{
	$.fn.yiiGridView.update('product-grid-add', {
		data: $(this).serialize() + '&idArea=' + $('#idTabArea').val()+'&idAreaProject=' + $('#idTabAreaProject').val()
	});
	
	$('#myModalAddProduct').append($('#container-modal-addProduct'));
	$('#container-modal-addProduct').show();

	var idArea = $('#idTabArea').val();
	var idAreaProject = $('#idTabAreaProject').val();
	var idBudget = $('#idBudget').val();
	var version = $('#version').val();
	
	$.post("<?php echo BudgetController::createUrl('AjaxGetTotalQty'); ?>",
			{
				idBudget:idBudget,
				version:version,
				idArea:idArea,
				idAreaProject:idAreaProject
			}
		).success(
			function(data){
				$('#total-qty').children().text(data);
			});
		
	$('#myModalAddProduct').modal('show');
	
	return false;
}

function editBudget(id,version)
{
	var params = "&id="+id+"&version="+version;
	window.location = "<?php echo BudgetController::createUrl("addItem")?>" + params; 
	return false;
}
function editBudgetByService(id,version)
{
	var params = "&id="+id+"&version="+version+"&byService=true";
	window.location = "<?php echo BudgetController::createUrl("addItem")?>" + params; 
	return false;
}

function exportBudget(id, version)
{
	var params = "&id="+id+"&version="+version;
	window.location = "<?php echo BudgetController::createUrl("exportToExcel")?>" + params; 
	return false;
}

function changeTimeProgramation(id, object,grid)
{
	statusStartSaving();	
	validateNumber(object);
	$.post(
			"<?php echo BudgetController::createUrl('AjaxSaveTimeProgramation')?>",
			{
				Id_budget_item: id,time_programation:$(object).val()
			}
			).success(function(data)
			{
				statusSaved();
				var response = jQuery.parseJSON(data);
				updateGridExtras();
				//alert("success");				
		}).error(function(data)
			{
			statusSavedError();				
		},"json");	
}
function changeTimeInstalation(id, object,grid)
{
	statusStartSaving();	
	validateNumber(object);
	$.post(
			"<?php echo BudgetController::createUrl('AjaxSaveTimeInstalation')?>",
			{
				Id_budget_item: id,time_instalation:$(object).val()
			}
			).success(function(data)
			{
				statusSaved();
				var response = jQuery.parseJSON(data);
				updateGridExtras();
				//alert("success");				
		}).error(function(data)
			{
			statusSavedError();				
		},"json");	
}

</script>
<div class="container" id="screenCrearPresupuesto">
  <h1 class="pageTitle">Presupuesto</h1>
  	<?php echo CHtml::hiddenField("idBudget",$model->Id,array("id"=>"idBudget"));?>
  	<?php echo CHtml::hiddenField("version",$model->version_number,array("id"=>"version"));?>
	<?php $this->renderPartial('_editBudgetHead',array('model'=>$model));?>
	<?php 
	if($byService)
	{
		$this->renderPartial('_editBudgetBodyByService',array(
					'model'=>$model,
					'modelProduct'=>$modelProduct,
					'modelBudgetItem'=>$modelBudgetItem,
					'priceListItemSale'=>$priceListItemSale,
					'areaProjects'=>$areaProjects,
					'modelBudgetItemGeneric'=>$modelBudgetItemGeneric,
		));
	}
	else 
	{
		$this->renderPartial('_editBudgetBody',array(
					'model'=>$model,
					'modelProduct'=>$modelProduct,
					'modelBudgetItem'=>$modelBudgetItem,
					'priceListItemSale'=>$priceListItemSale,
					'areaProjects'=>$areaProjects,
					'modelBudgetItemGeneric'=>$modelBudgetItemGeneric,
		));
	}
	?>
  
</div>

<div id="container-modal-addProduct" style="display: none">
<?php echo $this->renderPartial('_modalAddProduct', array( 'modelProducts'=>$modelProducts));?>
</div>
<!-- /container --> 