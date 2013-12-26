<a onclick="openNewBudget();" class="btn btn-primary superBoton" data-toggle="modal" data-target="#myModalCrearPresupuesto"><i class="fa fa-plus"></i> Crear Presupuesto</a>
 <?php		
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'budget-grid-open',
		'dataProvider'=>$modelBudgets->searchOpen(),
		'selectableRows' => 0,
		'filter'=>$modelBudgets,
		'summaryText'=>'',	
		'itemsCssClass' => 'table table-striped table-bordered tablaIndividual',
		'columns'=>array(	
				array(
					'name'=>'project_description',
					'value'=>'$data->project->description',
					'htmlOptions'=>array("style"=>"width:15%;"),
				),
				array(
					'name'=>'version_number',
					'value'=>'$data->version_number',
					'htmlOptions'=>array("style"=>"width:5%;"),
				),
				array(
					'name'=>'description',
					'value'=>'GreenHelper::cutString($data->description,40)',
					'htmlOptions'=>array("style"=>"width:20%;"),
				),
				array(
					'name'=>'percent_discount',
					'value'=>'$data->percent_discount',
					'htmlOptions'=>array("style"=>"width:5%;"),
				),
				array(
					'name'=>'date_creation',
					'value'=>'$data->date_creation',
					'htmlOptions'=>array("style"=>"width:10%;"),
				),
				array(
					'name'=>'date_inicialization',
					'value'=>'$data->date_inicialization',
					'htmlOptions'=>array("style"=>"width:10%;"),
				),
				array(
						'header'=>'Acciones',
						'value'=>function($data){
						$grid = "'budget-grid-open'";
							return '<button onclick="editBudget('.$data->Id.');" type="button" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> Editar</button>
									<button onclick="removeBudget('.$data->Id.');" type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i> Borrar</button>
            						<button onclick="closeVersion('.$data->Id.', '.$data->version_number.', '.$grid.');" type="button" class="btn btn-default btn-sm"><i class="fa fa-archive"></i> Cerrar</button>
            						<button onclick="exportBudget('.$data->Id.');"type="button" class="btn btn-default btn-sm"><i class="fa fa-download"></i> Descargar</button>';						
						},
						'type'=>'raw',
						'htmlOptions'=>array("style"=>"text-align:right;"),
				),
			),
		));		
		?>