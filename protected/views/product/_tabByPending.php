<?php 
$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'product-grid_pending',
		'dataProvider'=>$modelProducts->searchByPending(),
		'selectableRows' => 0,
		'filter'=>$modelProducts,
		'summaryText'=>'',	
		'itemsCssClass' => 'table table-striped table-bordered tablaIndividual',
		'columns'=>array(	
				'model',
				'part_number',				 
				array(
		 			'name'=>'brand_description',
					'value'=>'$data->brand->description',
				),
				array(
						'name'=>'height',
						'value'=>function($data){
							$value = $data->height;
							$field = "'height'";
							if($value == 0)
            					$value =  '<a href="#" onclick="openEditField('.$data->Id.', '.$field.');" class="label label-danger">Incompleto <i class="fa fa-pencil"></i></a>';
							else
								$value = $data->showVolume($data->height);
								
							return $value;
						},
						'type'=>'raw',
				),
				array(
						'name'=>'width',
						'value'=>function($data){
							$value = $data->width;
							$field = "'width'";
							if($value == 0)
            					$value =  '<a href="#" onclick="openEditField('.$data->Id.', '.$field.');" class="label label-danger">Incompleto <i class="fa fa-pencil"></i></a>';
							else 
								$value = $data->showVolume($data->width);
							
							return $value;
						},
						'type'=>'raw',
				),
				array(
						'name'=>'length',
						'value'=>function($data){
							$value = $data->length;
							$field = "'length'";
							if($value == 0)
								$value =  '<a href="#" onclick="openEditField('.$data->Id.', '.$field.');" class="label label-danger">Incompleto <i class="fa fa-pencil"></i></a>';
							else
								$value = $data->showVolume($data->length);
								
							return $value;
						},
						'type'=>'raw',
				),
				array(
						'name'=>'weight',
						'value'=>function($data){
							$value = $data->weight;
							$field = "'weight'";
							if($value == 0)
								$value =  '<a href="#" onclick="openEditField('.$data->Id.', '.$field.');" class="label label-danger">Incompleto <i class="fa fa-pencil"></i></a>';
							else
								$value = $data->showVolume($data->weight);
				
							return $value;
						},
						'type'=>'raw',
				),
				'profit_rate',
				array(
						'name'=>'dealer_cost',
						'value'=>function($data){
							$value = $data->dealer_cost;
							$field = "'dealer_cost'";
							if($value == 0)
            					$value =  '<a href="#" onclick="openEditField('.$data->Id.', '.$field.');" class="label label-danger">Incompleto <i class="fa fa-pencil"></i></a>';
							else
								$value = $data->showPrice($data->dealer_cost);
				
							return $value;
						},
						'type'=>'raw',
				),
				array(
						'name'=>'msrp',
						'value'=>function($data){
							$value = $data->msrp;
							$field = "'msrp'";
							if($value == 0)
            					$value =  '<a href="#" onclick="openEditField('.$data->Id.', '.$field.');" class="label label-danger">Incompleto <i class="fa fa-pencil"></i></a>';
							else
								$value = $data->showPrice($data->msrp);
				
							return $value;
						},
						'type'=>'raw',
				),
				array(
						'name'=>'short_description',
						'value'=>'$data->short_description',
						'htmlOptions'=>array("style"=>"width:20%;"),
				),
				array(
						'header'=>'Acciones',
						'value'=>function($data){
							return '<button type="button" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> Editar</button>
									<button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i> Borrar</button>';
						},
						'type'=>'raw',
						'htmlOptions'=>array("style"=>"text-align:right;"),
				),
			),
		));	
?>
