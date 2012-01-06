 
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'importer-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->errorSummary($modelContact); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($modelContact,'telephone_1'); ?>
		<?php echo $form->textField($modelContact,'telephone_1',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($modelContact,'telephone_1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($modelContact,'telephone_2'); ?>
		<?php echo $form->textField($modelContact,'telephone_2',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($modelContact,'telephone_2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($modelContact,'telephone_3'); ?>
		<?php echo $form->textField($modelContact,'telephone_3',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($modelContact,'telephone_3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($modelContact,'email'); ?>
		<?php echo $form->textField($modelContact,'email',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($modelContact,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($modelContact,'address'); ?>
		<?php echo $form->textField($modelContact,'address',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($modelContact,'address'); ?>
	</div>
	<div id="shippingParameter" class="row">
	<div class="gridTitle-decoration1">
		<div class="gridTitle1">
		Shipping Configuration
		</div>
	</div>
	
		<div class="row">
			<?php echo $form->labelEx($modelShippingParameter,'description'); ?>
			<?php echo $form->textField($modelShippingParameter,'description',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($modelShippingParameter,'description'); ?>
		</div>
		<div style="width: 45%; display: inline-block;;vertical-align: top;">
			<div class="gridTitle-decoration1">
				<div class="gridTitle1">
				Air
				</div>
			</div>
			<div class="row">
				<?php echo $form->labelEx($modelShippingParameterAir,'cost_measurement_unit'); ?>
				<?php echo $form->textField($modelShippingParameterAir,'cost_measurement_unit'); ?>
				<?php echo $form->error($modelShippingParameterAir,'cost_measurement_unit'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($modelShippingParameterAir,'weight_max'); ?>
				<?php echo $form->textField($modelShippingParameterAir,'weight_max',array('size'=>10,'maxlength'=>10)); ?>
				<?php echo $form->error($modelShippingParameterAir,'weight_max'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($modelShippingParameterAir,'Id_measurement_unit_cost'); ?>
				<?php				
					$measureType = MeasurementType::model()->findByAttributes(array('description'=>'weight'));
					echo $form->dropDownList($modelShippingParameterAir, 'Id_measurement_unit_cost', CHtml::listData(
		    			MeasurementUnit::model()->findAllByAttributes(array('Id_measurement_type'=>$measureType->Id)), 'Id', 'short_description')); 
				?>
				<?php echo $form->error($modelShippingParameterAir,'Id_measurement_unit_cost'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($modelShippingParameterAir,'length_max'); ?>
				<?php echo $form->textField($modelShippingParameterAir,'length_max',array('size'=>10,'maxlength'=>10)); ?>
				<?php echo $form->error($modelShippingParameterAir,'length_max'); ?>
			</div>
	
			<div class="row">
				<?php echo $form->labelEx($modelShippingParameterAir,'width_max'); ?>
				<?php echo $form->textField($modelShippingParameterAir,'width_max',array('size'=>10,'maxlength'=>10)); ?>
				<?php echo $form->error($modelShippingParameterAir,'widthv'); ?>
			</div>
	
			<div class="row">
				<?php echo $form->labelEx($modelShippingParameterAir,'height_max'); ?>
				<?php echo $form->textField($modelShippingParameterAir,'height_max',array('size'=>10,'maxlength'=>10)); ?>
				<?php echo $form->error($modelShippingParameterAir,'height_max'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($modelShippingParameterAir,'Id_measurement_unit_sizes_max'); ?>
				<?php				
					$measureType = MeasurementType::model()->findByAttributes(array('description'=>'linear'));
					echo $form->dropDownList($modelShippingParameterAir, 'Id_measurement_unit_sizes_max', CHtml::listData(
		    			MeasurementUnit::model()->findAllByAttributes(array('Id_measurement_type'=>$measureType->Id)), 'Id', 'short_description')); 
				?>
				<?php echo $form->error($modelShippingParameterAir,'Id_measurement_unit_sizes_max'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($modelShippingParameterAir,'days'); ?>
				<?php echo $form->textField($modelShippingParameterAir,'days',array('size'=>10,'maxlength'=>10)); ?>
				<?php echo $form->error($modelShippingParameterAir,'days'); ?>
			</div>
		</div>

		<div style="width: 45%; display: inline-block;vertical-align: top;">
			<div class="gridTitle-decoration1">
				<div class="gridTitle1">
				Maritime
				</div>
			</div>
			<div class="row">
				<?php echo $form->labelEx($modelShippingParameterMaritime,'cost_measurement_unit'); ?>
				<?php echo $form->textField($modelShippingParameterMaritime,'cost_measurement_unit'); ?>
				<?php echo $form->error($modelShippingParameterMaritime,'cost_measurement_unit'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($modelShippingParameterMaritime,'Id_measurement_unit_cost'); ?>
				<?php				
					$measureType = MeasurementType::model()->findByAttributes(array('description'=>'volume'));
					echo $form->dropDownList($modelShippingParameterMaritime, 'Id_measurement_unit_cost', CHtml::listData(
		    			MeasurementUnit::model()->findAllByAttributes(array('Id_measurement_type'=>$measureType->Id)), 'Id', 'short_description')); 
				?>
				<?php echo $form->error($modelShippingParameterMaritime,'Id_measurement_unit_cost'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($modelShippingParameterMaritime,'days'); ?>
				<?php echo $form->textField($modelShippingParameterMaritime,'days',array('size'=>10,'maxlength'=>10)); ?>
				<?php echo $form->error($modelShippingParameterMaritime,'days'); ?>
			</div>
			
		</div>

	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->