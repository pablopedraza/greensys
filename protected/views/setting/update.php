<?php
$this->breadcrumbs=array(
	'Settings'=>array('index'),
	$model->Id=>array('view','id'=>$model->Id),
	'Update',
);

$this->menu=array(
	array('label'=>'Create Setting', 'url'=>array('create')),
	array('label'=>'View Setting', 'url'=>array('view', 'id'=>$model->Id)),
	array('label'=>'Manage Setting', 'url'=>array('admin')),
);
?>

<h1>Update Setting <?php echo $model->Id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>