<?php		
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'budget-grid-approved',
		'dataProvider'=>$modelBudgets->searchApproved(),
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
					'name'=>'date_approved',
					'value'=>'$data->date_approved',
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
						'header'=>'Rentabilidad',
						'value'=>function($data){
							$rent = $data->ProfitPercenTotal;
							return '<span class="label '.($rent<50?'label-danger':'label-success').'">'.$rent.'%</span>';
						},
						'type'=>'raw',
						'htmlOptions'=>array("style"=>"width:12%;", "class"=>"align-right"),
						'headerHtmlOptions'=>array("class"=>"align-right"),
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
							return '<div class="buttonsTable"><button onclick="downloadPDF('.$data->Id.', '.$data->version_number.');" type="button" class="btn btn-default btn-sm"><i class="fa fa-download"></i> PDF</button></div>';						
						},
						'type'=>'raw',
						'htmlOptions'=>array("style"=>"text-align:right;"),
				),
			),
		));		
?>