<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	Yii::app()->lc->t('Manage'),
);

$this->menu=array(
	array('label'=>Yii::app()->lc->t('List User'), 'url'=>array('index')),
	array('label'=>Yii::app()->lc->t('Create User'), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo Yii::app()->lc->t('Manage Users')?></h1>

<p>
<?php 
	echo Yii::app()->lc->t('You may optionally enter a comparison operator ').'(<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>)'. Yii::app()->lc->t(' at the beginning of each of your search values to specify how the comparison should be done.'); 
	?>

</p>

<?php echo CHtml::link(Yii::app()->lc->t('Advanced Search'),'#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'username',
		'password',
		'email',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
