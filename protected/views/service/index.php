<?php
$this->breadcrumbs=array(
	'Services',
);

$this->menu=array(
	array('label'=>'Create Service', 'url'=>array('create')),
	array('label'=>'Manage Service', 'url'=>array('admin')),
	array('label'=>'Assing Categories', 'url'=>array('serviceCategory')),
);
?>

<h1>Services</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
