<?php 
$settings = new Settings();
?>
<div class="row contenedorPresu noBorder">
    <div class="col-sm-12">
      <div class="tituloFinalPresu">Configurar Textos</div>
      <ul class="nav nav-tabs">
        <li class="active"><a id="tabServices" href="#tabDescripciones" data-toggle="tab">Descripciones Servicios</a></li>
        <li><a id="tabServicesNote" href="#tabNotas" data-toggle="tab">Notas Servicios</a></li>
        <li><a id="tabServicesCondiciones" href="#tabCondiciones" data-toggle="tab">Condiciones de Contrataci&oacute;n</a></li>
        <li><a id="tabNoteVersions" href="#tabVersionNote" data-toggle="tab">Nota de Versi&oacute;n</a></li>
        </ul>
        <div class="tab-content">
<div class="tab-pane active" id="tabDescripciones">

  <?php 
$projectService = new ProjectService();
$projectService->Id_project = $model->Id_project;
$projectService->Id_budget = $model->Id;
$projectService->version_number = $model->version_number;
	$this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'project-service-grid',
					'dataProvider'=>$projectService->search(),
					'summaryText'=>'',
					'selectableRows' => 0,
					'itemsCssClass' => 'table table-striped table-bordered tablaIndividual',
					'ajaxUrl'=>BudgetController::createUrl('AjaxUpdateProjectServiceGrid',array("Id"=>$model->Id,"version_number"=>$model->version_number)),
					'columns'=>array(
							array(
									'header'=>'Orden',
									'value'=>function($data,$index){
										$idService = isset($data->order)?$data->order:"0";
										return '<div class="buttonsTableOrder">
													<button type="button" class="btn btn-primary btn-xs" onclick="downService('.$data->Id_budget.','.$data->version_number.','.$data->Id_service.','.$data->Id_project.',\'project-service-grid\')">
														<i class="fa fa-angle-down fa-lg"></i></i>
													</button><button type="button" class="btn btn-primary btn-xs noMargin" onclick="upService('.$data->Id_budget.','.$data->version_number.','.$data->Id_service.','.$data->Id_project.',\'project-service-grid\')">
														<i class="fa fa-angle-up fa-lg"></i></i>
													</button><br/>
													<button type="button" class="btn btn-default btn-xs" onclick="downServiceToBottom('.$data->Id_budget.','.$data->version_number.','.$data->Id_service.','.$data->Id_project.',\'project-service-grid\')">
														<i class="fa fa-angle-double-down fa-lg"></i></i>
													</button><button type="button" class="btn btn-default btn-xs noMargin" onclick="upServiceToAbove('.$data->Id_budget.','.$data->version_number.','.$data->Id_service.','.$data->Id_project.',\'project-service-grid\')">
														<i class="fa fa-angle-double-up fa-lg"></i></i></button>
												</div>';						
									},
									'type'=>'raw',
										'htmlOptions'=>array("style"=>"width:52px;"),
										'headerHtmlOptions'=>array("style"=>"width:52px;"),
							),
							array(
									'header'=>'Servicios',
									'value'=>'$data->service->description',
									'type'=>'raw'
							),					
							array(
									'header'=>'Descripci&oacute;n',
									'value'=>'GreenHelper::cutString($data->long_description==""?$data->service->long_description:$data->long_description,130)',
									'type'=>'raw'
							),
							array(
									'name'=>'Acciones',
									'value'=>function($data){
											return '<button type="button" class="btn btn-default btn-sm" onclick="editProjectService('.$data->Id_project.','.$data->Id_service.','.$data->Id_budget.','.$data->version_number.');" ><i class="fa fa-pencil"></i> Editar</button>';
									},
									'type'=>'raw',
									'htmlOptions'=>array("style"=>"text-align:right;"),
									'headerHtmlOptions'=>array("style"=>"text-align:right;"),
							),
							),
					));
        ?>
</div> 
    <!-- /.tab1 -->
<div class="tab-pane" id="tabNotas">

  <?php 
$projectService = new ProjectService();
$projectService->Id_project = $model->Id_project;
$projectService->Id_budget = $model->Id;
$projectService->version_number = $model->version_number;
	$this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'project-service-note-grid',
					'dataProvider'=>$projectService->search(),
					'summaryText'=>'',
					'selectableRows' => 0,
					'itemsCssClass' => 'table table-striped table-bordered tablaIndividual',
					'ajaxUrl'=>BudgetController::createUrl('AjaxUpdateProjectServiceGrid',array("Id"=>$model->Id,"version_number"=>$model->version_number)),
					'columns'=>array(
							array(
									'header'=>'Servicios',
									'value'=>'$data->service->description',
									'type'=>'raw'
							),					
							array(
									'header'=>'Notas',
									'value'=>'GreenHelper::cutString($data->note==""?$data->service->note:$data->note,130)',
									'type'=>'raw'
							),
							array(
									'name'=>'Acciones',
									'value'=>function($data){
										return '<button type="button" class="btn btn-default btn-sm" onclick="editProjectServiceNote('.$data->Id_project.','.$data->Id_service.','.$data->Id_budget.','.$data->version_number.');" ><i class="fa fa-pencil"></i> Editar</button>';
									},
									'type'=>'raw',
									'htmlOptions'=>array("style"=>"text-align:right;"),
									'headerHtmlOptions'=>array("style"=>"text-align:right;"),
							),
							),
					));
        ?>
</div> 
    <!-- /.tab2 -->
    
<div class="tab-pane" id="tabCondiciones">
<table class="table table-bordered tablaIndividual">

<tbody>
<tr>
<td><?php echo CHtml::activeCheckBox($model, 'print_clause', array('onchange'=>'changePrintChk(this,'.$model->Id.', '.$model->version_number.');'));?> Imprimir en Presupuesto</td>
<td>&nbsp;</td>
</tr>
<tr>
<td width="85%">
<div id="budget-clause-description" class="budgetClausulas clauseScroll">
<?php echo $model->clause_description; ?>
</div></td>
<td valign="top" class="align-right">
<div class="buttonsPresuClause">
<button onclick="openUpdateClause(<?php echo $model->Id;?>, <?php echo $model->version_number;?>);" class="btn btn-default btn-sm" data-toggle="modal" ><i class="fa fa-pencil"></i> Editar</button>
<button onclick="updateToDefaultClause(<?php echo $model->Id;?>, <?php echo $model->version_number;?>);" type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Actualizar</button></div>
</td>
</tr>
</tbody>
</table>

</div> 
    <!-- /.tab3 -->
<div class="tab-pane" id="tabVersionNote">
<table class="table table-bordered tablaIndividual">

<tbody>
<tr>
<td><?php echo CHtml::activeCheckBox($model, 'print_note_version', array('onchange'=>'changePrintNoteChk(this,'.$model->Id.', '.$model->version_number.');'));?> Imprimir en Presupuesto</td>
<td>&nbsp;</td>
</tr>
<tr>
<td width="85%">
<div id="budget-note-version" class="budgetClausulas clauseScroll">
<?php echo $model->note_version; ?>
</div></td>
<td valign="top" class="align-right">
<div class="buttonsPresuClause">
	<button onclick="openUpdateNoteVersion(<?php echo $model->Id;?>, <?php echo $model->version_number;?>);" class="btn btn-default btn-sm" data-toggle="modal" ><i class="fa fa-pencil"></i> Editar</button>
</div>
</td>
</tr>
</tbody>
</table>

</div> 
    <!-- /.tab4 -->
</div>
    <!-- /.tab-content -->
      </div>
    <!-- /.col-sm-12 -->
  </div>


  <div id="myModalChangeClause" class="modal fade in" aria-hidden="false">
  <?php
    echo $this->renderPartial('_modalUpdateClause',array(
						'model'=>$model,));
	?>

</div>
<div id="myModalChangeNoteVersion" class="modal fade in" aria-hidden="false">
  <?php
    echo $this->renderPartial('_modalUpdateNoteVersion',array(
						'model'=>$model,));
	?>

</div>
  