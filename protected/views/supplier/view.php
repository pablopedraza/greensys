<?php
$this->breadcrumbs=array(
	'Suppliers'=>array('index'),
	$model->business_name,
);

$this->menu=array(
	array('label'=>'List Supplier', 'url'=>array('index')),
	array('label'=>'Create Supplier', 'url'=>array('create')),
	array('label'=>'Update Supplier', 'url'=>array('update', 'id'=>$model->Id)),
	array('label'=>'Delete Supplier', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->Id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Supplier', 'url'=>array('admin')),
);

Yii::app()->clientScript->registerScript('priceListItem', "
$('#addContact').hover(
function () {
	$(this).attr('src','images/add_contact_light.png');
  },
  function () {
	$(this).attr('src','images/add_contact.png');
  }
);
$('#viewContact').hover(
function () {
	$(this).attr('src','images/view_contact_light.png');
  },
  function () {
	$(this).attr('src','images/view_contact.png');
  }
);

");
?>

<h1>View Supplier</h1>

<div class="left">

	<?php
	$this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'cssFile'=>Yii::app()->baseUrl . '/css/detail-view-blue.css',
		'attributes'=>array(
			'business_name',
			array('label'=>$model->getAttributeLabel('description'),
				'type'=>'raw',
				'value'=>$model->contact->description
			),
			array('label'=>$model->getAttributeLabel('email'),
				'type'=>'raw',
				'value'=>$model->contact->email
			),
			array('label'=>$model->getAttributeLabel('telephone_1'),
				'type'=>'raw',
				'value'=>$model->contact->telephone_1
			),
			array('label'=>$model->getAttributeLabel('telephone_2'),
				'type'=>'raw',
				'value'=>$model->contact->telephone_2
			),
			array('label'=>$model->getAttributeLabel('telephone_3'),
				'type'=>'raw',
				'value'=>$model->contact->telephone_3
			),
			array('label'=>$model->getAttributeLabel('address'),
				'type'=>'raw',
				'value'=>$model->contact->address
			),
		),
	)); 
	?>
</div>

<div class="right" style="margin-left:1px; width: 48%; ">
	<b><?php echo CHtml::encode($model->getAttributeLabel('link')); ?>:</b>
	<?php
	$hyperLinks = CHtml::listData($modelHyperlink, 'Id','description');
	
	$this->widget('ext.linkcontainer.linkcontainer', array(
		'id'=>'linkContainer',	// default is class="ui-sortable" id="yw0"
		'items'=>$hyperLinks,
		'mode'=>'show'
	));
	?>
	<div class="gridTitle-decoration1" style="display: inline-block; width: 98%;height: 35px;background-color: #B7D6E7">
		<div class="gridTitle1" style="display: inline-block;position: relative; width: 90%;vertical-align: top; margin-top: 4px;">
			Products
		</div>
		<div style="display: inline-block;position: relative; width: 20px;height:20px; vertical-align: middle;">
		<?php
		echo CHtml::link( CHtml::image('images/view_contact.png','view Contacts',array(
												                                'title'=>'View contact',
												                                'style'=>'width:30px;',
												                                'id'=>'viewContact',
                                												)
                            ),SupplierController::createUrl('AjaxViewContact',array('id'=>$model->Id)));
		?>

		</div>
		<div style="display: inline-block;position: relative; width: 20px;height:20px; vertical-align: middle;">
		<?php
		echo CHtml::link( CHtml::image('images/add_contact.png','add Contact',array(
												                                'title'=>'Add contact',
												                                'style'=>'width:30px;',
												                                'id'=>'addContact',
                                												)
                            ),SupplierController::createUrl('AjaxAddContact',array('id'=>$model->Id)));
		?>

		</div>
	</div>
</div>