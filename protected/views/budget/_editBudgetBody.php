  
   <div class="row contenedorPresu">
    <div class="col-sm-12">
      <div class="tituloFinalPresu">Productos</div>
    
      <ul class="nav nav-tabs navTabsPencil">
        <?php 
        $first = true;
        $idArea = null;
        foreach($areaProjects as $item)	{ ?>
        <li class="<?php echo ($first?'active':'');?>"><a onclick="changeTab(<?php echo $item->Id_area;?>)" href="#itemArea_<?php echo $item->Id.'_'.$item->Id_area;?>" data-toggle="tab"><?php echo $item->area->description?> </a><a class="tabEdit"><i class="fa fa-pencil"></i></a></li>
		<?php if($first)
	        {
	        	$idArea = $item->Id_area;
	        	$first= false;
	        }
		}
		echo CHtml::hiddenField("idTabArea",$idArea, array('id'=>'idTabArea'));
		?>
      
        <li class="pull-right">
          <div class="btn-group btnAlternateView">
  <button type="button" class="btn btn-default active">Áreas</button>
  <button type="button" class="btn btn-default">Servicios</button>
</div>
        </li>
                <li class="pull-right"><button onclick="addProduct(<?php echo $model->Id .', '. $model->version_number;?>);" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalAgregarProductos"><i class="fa fa-plus"></i> Agregar Productos</button></li>
      </ul>
      <div class="tab-content">
        <?php
        $first = true;
        foreach($areaProjects as $item)	{ ?>
        <div class="tab-pane <?php echo $first?'active':'';?>" id="itemArea_<?php echo $item->Id.'_'.$item->Id_area;?>">
        <?php 
	        
        	if($first)
        		$first = false;
	        $modelBudgetItem->Id_area = $item->Id_area;
	        $modelBudgetItem->Id_area_project = $item->Id;
	        
	        echo $this->renderPartial('_tabEditBudgetByArea',array(
						'model'=>$model,
						'modelProduct'=>$modelProduct,
						'modelBudgetItem'=>$modelBudgetItem,
						'priceListItemSale'=>$priceListItemSale,
						'areaProject'=>$item,
						'modelBudgetItemGeneric'=>$modelBudgetItemGeneric,
			));
		?>

   </div>
  		<?php } ?>   
   </div>
    </div>
    </div>
    <div class="row contenedorPresu">
    <div class="col-sm-6">
      <div class="tituloFinalPresu">Extra</div>
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tabRecargos" data-toggle="tab">Recargos</a></li>
        <li><a href="#tabDescripciones" data-toggle="tab">Descripci&oacute;n de Servicios</a></li>
        <li class="pull-right"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalAgregarDesc"><i class="fa fa-plus"></i> Agregar</button></li>
        </ul>
        <div class="tab-content">
        <div class="tab-pane active" id="tabRecargos">
        <?php
	$settings = new Settings();

	$selectPrice='"<div class=\"precioTabla\"><div class=\"precioTablaValor\">".$data->price." "."<div class=\"usd\">'.$settings->getEscapedCurrencyShortDescription().'</div></div>'.
			'<button id=\"btn_price_".$data->Id."\" type=\"button\" class=\"btn btn-primary btn-xs pull-right dropdown-toggle miniEdit\" onclick=\"fillAndOpenDD(".$data->Id.");\">
             <i class=\"fa fa-pencil\"></i>
             </button>".'.
	             '"<ul id=\"ul_price_".$data->Id."\" class=\"dropdown-menu superDropdown\" role=\"menu\" aria-labelledby=\"dropdownMenu1\">
  </ul></div>"';
	
	$this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'budget-item-generic',
					'dataProvider'=>$modelBudgetItem->searchGenericItem(),
					'summaryText'=>'',
					'selectableRows' => 0,
					'itemsCssClass' => 'table table-striped table-bordered tablaIndividual',
					'columns'=>array(
							'description',
							array(
								'name'=>'quantity',
								'value'=>
                                    	'CHtml::textField("txtQuantityGenericItem",
												$data->quantity,
												array(
														"id"=>$data->Id,
														"class"=>"form-control inputSmall",
													)
											)',
			
								'type'=>'raw',
								'htmlOptions'=>array("style"=>"text-align:right;"),
							),
					array(
							'name'=>'price',
							'value'=>$selectPrice,
							'type'=>'raw',
					),
					array(
						'name'=>'discount',
						'value'=>
						'"<div class=\"bloqueDescuento\"> ".CHtml::textField("txtDiscount","$data->discount",array("id"=>"discount_".$data->Id,"onchange"=>"changeDiscount(".$data->Id.",this)","class"=>"form-control inputMed",))."<div class=\"radioTipo\"><div class=\"radio\">
				  <label>
				    <input type=\"radio\" name=\"optionsRadios_".$data->Id."\" id=\"discount_type_".$data->Id."\" value=\"0\" onclick=\"changeDiscountType(".$data->Id.",this);\" ".($data->discount_type==0?"checked":"").">
				    <div class=\"usd\">%</div>
				  </label>
				</div>
				<div class=\"radio\">
				  <label>
				    <input type=\"radio\" name=\"optionsRadios_".$data->Id."\" id=\"discount_type_".$data->Id."\" value=\"1\" onclick=\"changeDiscountType(".$data->Id.",this);\" ".($data->discount_type==1?"checked":"").">
				    <div class=\"usd\">USD</div>
				  </label>
				</div></div></div>"',
						'type'=>'raw',
						'htmlOptions'=>array(),
				),
							
					array(
							'name'=>'Total',
							'value'=>
							'CHtml::openTag("span",array("id"=>"total_price_".$data->Id, "class"=>"label label-primary labelPrecio")).$data->totalPrice." ".'.
							'CHtml::openTag("div",array("class"=>"usd"))."'.$settings->getEscapedCurrencyShortDescription().'".CHtml::closeTag("div").CHtml::closeTag("span")',
							'type'=>'raw',
					),

							),
					));
        ?>

</div> 
    <!-- /.tab1 -->
<div class="tab-pane" id="tabDescripciones">
<table class="table table-striped table-bordered tablaIndividual">
<thead>
<tr>
<th>Servicio</th>
<th>Descripcion</th>
<th style="text-align:center;">Acciones</th>
</tr></thead>
<tbody>
<tr>
<td>Home Theater</td>
<td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet placerat volutpat....</td>
<td style="text-align:center;">
<a class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModalEditarDesc"><i class="fa fa-pencil"></i> </a>
</td>
</tr>
<tr>
<td>Multiroom Audio</td>
<td>Nam sit amet dolor at nisi dapibus lobortis. Curabitur elementum dolor a turpis...</td>
<td style="text-align:center;">
<a class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModalEditarDesc"><i class="fa fa-pencil"></i> </a>
</td>
</tr>
<tr>
<td>Control de Iluminaci&oacute;n</td>
<td>Donec nec turpis</td>
<td style="text-align:center;"> 
<a class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModalEditarDesc"><i class="fa fa-pencil"></i> </a>
</td>
</tr></tbody>
</table>
</div> 
    <!-- /.tab2 -->
</div>
    <!-- /.tab-content -->
      </div>
    <!-- /.col-sm-6 -->
  <div class="col-sm-6">
  </div>
  <div class="col-sm-6">
  <div class="tituloFinalPresu">Total</div>
<table class="table tablePresuTotal">
        <tbody>
          <tr>
            <td width="20%" valign="middle"  width="20%">Subtotal</td>
            <td width="30%">&nbsp;</td>
            <td valign="middle"  align="right" class="bold"><div class="usd">USD</div> 2000</td>
          </tr>
          <tr>
            <td valign="middle" >Discount</td>
            <td><input type="model" id="campoPrecio" class="form-control" value="0"> %</td>
            <td align="right" valign="middle" class="bold"> <div class="usd">USD</div> 2000</td>
          </tr>
          <tr class="superTotal">
            <td valign="middle" >Total</td>
            <td>&nbsp;</td>
            <td valign="middle"  align="right" class="bold">USD 2000</td>
          </tr>
        </tbody>
      </table>
  </div>
  </div>
    
  <div class="row navbar-fixed-bottom">
    <div class="col-sm-12">
      <div class="statusFloatSaving">
        <i class="fa fa-spinner fa-spin fa-fw"></i> Guardando
      </div>
      <div class="statusFloatSaved">
        <i class="fa fa-check fa-fw"></i> Guardado
        </div>
    </div>
    <!-- /.col-sm-12 --> 
  </div>
  <!-- /.row --> 
  
  
  <div class="modal fade in" id="myModalEditarDesc" aria-hidden="false">
  <div class="modal-dialog">
  <div class="modal-content">
  <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 class="modal-title">Editar Descripci&oacute;n de Servicio</h4>
      </div>
      <div class="modal-body">

<form role="form">
  <div class="form-group">
    <label for="campoDealerCost">Home Theater</label>
        <textarea class="form-control"  rows="6" id="Product_output_terminals">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet placerat volutpat. Vestibulum aliquet orci sed arcu imperdiet convallis. Vivamus iaculis et leo cursus ornare. Nullam tristique adipiscing volutpat. Nulla in purus eget felis aliquam vulputate. Nunc vitae egestas diam. 
 </textarea>
  </div>
</form>
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">
function changeService(id, object)
{

	$.post(
			"<?php echo BudgetController::createUrl('AjaxSaveService')?>",
			{
				Id_budget_item: id,Id_service:$(object).val()
			}
			).success(function(data)
			{
		}).error(function(data)
			{
		},"json");	
}

function changeDiscount(id, object)
{
	validateNumber(object);
	$.post(
			"<?php echo BudgetController::createUrl('AjaxSaveDiscountValue')?>",
			{
				Id_budget_item: id,discount:$(object).val()
			}
			).success(function(data)
			{
				var response = jQuery.parseJSON(data);
				$("#total_price_"+id).html(response.total_price+" <div class=\"usd\"><?php echo $settings->getEscapedCurrencyShortDescription()?></div>");
				$(object).val(response.discount);
				//setTotals();
				//alert("success");				
		}).error(function(data)
			{
				//alert("error");				
		},"json");	
}
function changeQuantity(id, object)
{
	validateNumber(object);
	$.post(
			"<?php echo BudgetController::createUrl('AjaxSaveQuantity')?>",
			{
				Id_budget_item: id,quantity:$(object).val()
			}
			).success(function(data)
			{
				var response = jQuery.parseJSON(data);
				$("#total_price_"+id).html(response.total_price+" <div class=\"usd\"><?php echo $settings->getEscapedCurrencyShortDescription()?></div>");
				$(object).val(response.quantity);
				//setTotals();
				//alert("success");				
		}).error(function(data)
			{
				//alert("error");				
		},"json");	
}

function changeDiscountType(id, object)
{
	$.post(
			"<?php echo BudgetController::createUrl('AjaxSaveDiscountType')?>",
			{
				Id_budget_item: id,discount_type:$(object).val()
			}
			).success(function(data)
			{
				var response = jQuery.parseJSON(data);
				$("#total_price_"+id).html(response.total_price+" <div class=\"usd\"><?php echo $settings->getEscapedCurrencyShortDescription()?></div>");
				//setTotals();
		}).error(function(data)
			{
				//alert("error");				
		},"json");	
}
function deleteBudgetItem(id,idArea)
{
	if(confirm("¿Esta seguro que desea eliminar este item?"))
	{
		$.post(
				'<?php echo BudgetController::createUrl('AjaxDeleteBudgetItem')?>',
				 {
				 	id: id,
				 },'json').success(
					function(data) 
					{
						 $.fn.yiiGridView.update('budget-item-grid_'+idArea); 
					}
				);		
	}	
 	return false;
}

function fillAndOpenDD(id)
{
	$(".dropdown-menu").removeClass("open");
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
			);		
	
 	return false;
}
<!--

//-->
</script>
