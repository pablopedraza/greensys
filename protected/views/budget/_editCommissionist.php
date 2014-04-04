<script type="text/javascript">
function addCommissionist()
{	
	var value = $("#commissionist_value").val()
	if(value > 0)
	{
		$.post("<?php echo BudgetController::createUrl('AjaxAddCommissionist'); ?>",
			{
				idBudget:<?php echo $modelBudget->Id;?>,
				version:<?php echo $modelBudget->version_number;?>,
				name:$("#commissionist_name").val(),
				last_name:$("#commissionist_last_name").val(),
				value:value
			}
		).success(
			function(data){
				$("#commissionist_name").val(''),
				$("#commissionist_last_name").val(''),
				$("#commissionist_value").val(''),
				$.fn.yiiGridView.update('commissionist-grid');
			});
		return false;
	}
}
function removeCommissionist(obj)
{		
	if(confirm("¿Seguro desea eliminar el comisionista?"))
	{
		
		$.post("<?php echo BudgetController::createUrl('AjaxRemoveCommissionist'); ?>",
			{
				idBudget:<?php echo $modelBudget->Id;?>,
				version:<?php echo $modelBudget->version_number;?>,
				idPerson:$(obj).attr("idperson")
			}
		).success(
			function(data){
				$.fn.yiiGridView.update('commissionist-grid');
			});
		return false;
	}
}
</script>

<div class="row">
  <div class="col-sm-12">
  <table class="table table-condensed tablaIndividual">
  <thead>
  <tr>
  <th colspan="2">Comisionista</th>
  <th class="align-right">Porcentaje</th>
  <th class="align-right">Acciones</th>
  </tr>
  </thead>
  <tbody>
  <tr>
  <td><input id="commissionist_name" class="form-control" placeholder="Nombre"></td>
  <td><input id="commissionist_last_name" class="form-control" placeholder="Apellido"></td>
  <td class="align-right"><input onkeyup="validateNumber(this);" id="commissionist_value" class="form-control align-right formHasLabel inputSmall" placeholder="0.00">%</td>
  <td class="align-right"><button type="button" onclick="addCommissionist();" class="btn btn-primary btn-sm noMargin"><i class="fa fa-plus"></i> Agregar</button></td>
  </tr>

  
  </tbody>
  </table>
  </div>
  <div class="col-sm-12">
  <?php
  $this->widget('zii.widgets.grid.CGridView', array(
  		'id'=>'commissionist-grid',
  		'dataProvider'=>$modelCommissionists->search(),
  		'selectableRows' => 0,
  		'summaryText'=>'',
		'hideHeader'=>true,
		'emptyText' => 'Ops, no hay comisionistas!',
  		'itemsCssClass' => 'table table-condensed tablaIndividual',
		'ajaxUrl'=>BudgetController::createUrl('AjaxUpdateCommissionistGrid',array("Id"=>$modelBudget->Id,"version_number"=>$modelBudget->version_number)),
  		'columns'=>array(
  				array(
  						'value'=>'$data->person->name',
  				),
  				array(
  						'value'=>'$data->person->last_name',
  				),
  				array(
						'value'=>function($data){
							return '<input onkeyup="validateNumber(this);" class="form-control align-right formHasLabel inputSmall" placeholder="Comisi&oacute;n" value="'.$data->percent_commission.'">%';
						},
						'type'=>'raw',
						'headerHtmlOptions'=>array("style"=>"align:right;"),
  				),  				
  				array(  						
  						'value'=>function($data){
  							return '<button type="button" idperson="'.$data->Id_person.'" onclick="removeCommissionist(this);" class="btn btn-default btn-sm noMargin"><i class="fa fa-trash-o"></i> Borrar</button>';
  						},
  						'type'=>'raw',
  						'headerHtmlOptions'=>array("style"=>"align:right;"),
  		),
  		),
  ));
  
  ?>
  </div>
  </div>