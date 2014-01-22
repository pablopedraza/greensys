<?php		
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'budget-grid-waiting',
		'dataProvider'=>$modelBudgets->searchWaiting(),
		'selectableRows' => 0,
		'filter'=>$modelBudgets,
		'summaryText'=>'',	
		'itemsCssClass' => 'table table-striped table-bordered tablaIndividual',
		'columns'=>array(	
				array(
					'name'=>'project_description',
					'value'=>'$data->project->fullDescription',
				),
				array(
					'name'=>'date_close',
					'value'=>'$data->date_close',
					'htmlOptions'=>array("style"=>"width:10%;", "class"=>"align-right"),
					'headerHtmlOptions'=>array("class"=>"align-right"),
				),
				array(
					'name'=>'version_number',
					'value'=>'$data->version_number',
					'htmlOptions'=>array("style"=>"width:10%;", "class"=>"align-right"),
					'headerHtmlOptions'=>array("class"=>"align-right"),
				),
				array(
					'name'=>'description',
					'value'=>'GreenHelper::cutString($data->description,40)',
					'htmlOptions'=>array("style"=>"width:20%;"),
				),
				array(
					'name'=>'percent_discount',
					'value'=>'$data->percent_discount',
					'htmlOptions'=>array("style"=>"width:5%;", "class"=>"align-right"),
					'headerHtmlOptions'=>array("class"=>"align-right"),
				),
				array(
					'name'=>'date_creation',
					'value'=>'$data->date_creation',
					'htmlOptions'=>array("style"=>"width:15%;", "class"=>"align-right"),
					'headerHtmlOptions'=>array("class"=>"align-right"),
				),
				array(
					'name'=>'date_inicialization',
					'value'=>'$data->date_inicialization',
					'htmlOptions'=>array("style"=>"width:10%;", "class"=>"align-right"),
					'headerHtmlOptions'=>array("class"=>"align-right"),
				),
				array(
						'header'=>'Acciones',
						'value'=>function($data){
						$grid = "'budget-grid-waiting'";
							return '<div class="btn-group">
    									<button data-toggle="dropdown" class="btn btn-default btn-sm dropdown-toggle">Estado <i class="fa fa-caret-down"></i></button>
    										<ul class="dropdown-menu">
										        <li><a onclick="openChangeStateBudget('.$data->Id.', '.$data->version_number.', 1)" href="#"><i class="fa fa-refresh"></i> Re-Abrir</a></li>
										        <li><a onclick="approveBudget('.$data->Id.', '.$data->version_number.', '.$grid.')" href="#"><i class="fa fa-check"></i> Aprobado</a></li>
										        <li><a onclick="openChangeStateBudget('.$data->Id.', '.$data->version_number.', 4)" href="#"><i class="fa fa-times-circle"></i> Cancelado</button></a></li>
										    </ul>
									</div>
            						<button onclick="downloadPDF('.$data->Id.', '.$data->version_number.');" type="button" class="btn btn-default btn-sm"><i class="fa fa-download"></i> PDF</button>';						
						},
						'type'=>'raw',
						'htmlOptions'=>array("style"=>"text-align:right;"),
				),
			),
		));		
?>