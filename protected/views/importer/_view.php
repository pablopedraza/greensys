<div class="view">


	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->contact->description), array('view', 'id'=>$data->Id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->contact->getAttributeLabel('telephone_1')); ?>:</b>
	<?php echo CHtml::encode($data->contact->telephone_1); ?>
	<br />

	<b><?php echo CHtml::encode($data->contact->getAttributeLabel('telephone_2')); ?>:</b>
	<?php echo CHtml::encode($data->contact->telephone_2); ?>
	<br />

	<b><?php echo CHtml::encode($data->contact->getAttributeLabel('telephone_3')); ?>:</b>
	<?php echo CHtml::encode($data->contact->telephone_3); ?>
	<br />

	<b><?php echo CHtml::encode($data->contact->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->contact->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->contact->getAttributeLabel('address')); ?>:</b>
	<?php echo CHtml::encode($data->contact->address); ?>
	<br />

	<b><?php echo CHtml::encode($data->shippingParameters[0]->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->shippingParameters[0]->description); ?>
	<br />

</div>