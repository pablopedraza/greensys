<?php 
Yii::app()->clientScript->registerScript(__CLASS__.'#review-view-data'.$data->Id, "
");
$needConfirmation = $dataUserGroupNote->need_confirmation;
$confirmed = $dataUserGroupNote->confirmed;
$declined = $dataUserGroupNote->declined;
$isOwner = User::isOwnerOf($data);
?>

<div class="review-single-view" id="<?php echo $data->Id?>" >
	<div class="view-text-date" style="display:none;"><?php echo $data->change_date;?></div>
	<div class="review-text-simple-note" style="display:none;">
		<div class="review-single-view-actions">
			<div class="review-single-view-autor">
				<?php
				echo CHtml::encode($data->user->name.' '.$data->user->last_name);								
				?>
			</div>
		</div>
		<div class="review-single-view-actions"  style="height: 50px">
			<div class="review-single-view-actions-need-conf">
				<?php
				echo CHtml::openTag('div',array('class'=>'review-note-users-groups'));								
					echo CHtml::decode('Para: ');
				echo CHtml::closeTag('div');								
				$first = true;
				foreach ($data->userGroupNotes as $item){
					if($item->addressed){
						if(!$first)
						{
							echo CHtml::openTag('div',array('class'=>'review-note-users-groups'));								
								echo CHtml::encode(',');								
							echo CHtml::closeTag('div');								
						}
						$first = false;							
						$group = User::getCurrentUserGroup();
						if($item->Id_user_group==$group->Id)
						{
							$user=User::getCurrentUser();
							
							echo CHtml::openTag('div',array('class'=>'review-note-users-names'));								
								echo CHtml::encode($user->name.' '.$user->last_name);								
							echo CHtml::closeTag('div');								
						}
						else 
						{
							echo CHtml::openTag('div',array('class'=>'review-note-users-groups'));								
								echo CHtml::encode(' '.$item->userGroup->description);								
							echo CHtml::closeTag('div');								
						}
					}
				}
				?>
			</div>
			<div class="review-single-view-actions-conf">
				<?php 	 		
		 		if($needConfirmation)
		 		{
		 			if($confirmed || $declined)
		 			{
		 				$color = 'background-color:';
		 				$color.=($confirmed)?'#80e765;color:black;':'#ed5656;color:black;';
		 				echo CHtml::openTag('div',
		 					array(
		 						'class'=>'review-confirmed-note-btn review-confirm-note-btn-pos',
		 						'style'=>$color,
		 					)
		 				);
		 				echo ($confirmed)?'Confirmardo':'Rechazado';
		 				echo CHtml::closeTag('div');	 				
		 				echo CHtml::openTag('div',array('class'=>'review-conf-note-pos'));
		 				echo '('. $dataUserGroupNote->getConfirmDate() .')';
		 				echo CHtml::closeTag('div');
		 			}
		 			else 
		 			{
		 				echo CHtml::openTag('div',
		 					array(
 						 			'class'=>'review-confirmed-note-btn review-confirm-note-btn-pos',
 						 			'style'=>'background-color:#80e765;color:black;',
		 						)
		 					);
		 				echo 'Auto Conf';
		 				echo CHtml::closeTag('div');
		 				echo CHtml::openTag('div',array('class'=>'review-conf-note-pos'));
		 				echo '('. $dataUserGroupNote->getDueDate() .')';
		 				echo CHtml::closeTag('div');
		 			}
		 		}
		 	?>
			</div>
		</div>
			<div class="wall-action-edit-main-note" >
			<p class="single-formated-text"><?php echo $data->note;?></p>
			</div>
	</div>		
	<div class="review-multimedia-conteiner" style="display:none;">
	<div id='review_image<?php echo $data->Id?>' class="review-text-images" style="display:none;">
			
	<?php
	
	$images = array();
	$height=0;
	foreach($data->multimedias as $itemMultimedia)
	{
		if($itemMultimedia->Id_multimedia_type!=1) continue;
		$image= array();
		$image['image'] = "images/".$itemMultimedia->file_name;
		$image['small_image'] = "images/".$itemMultimedia->file_name_small;
		$image['caption'] = $itemMultimedia->description;
		$images[]=$image;
	}
	if(sizeof($images)>0)
	{
	
		$this->widget('ext.photoswipe.photoswipe', array(
												'images'=>$images,
												'Id'=>$data->Id,
		));
	}
	?>
	</div>
	<div class="review-text-docs">
		<?php 
			if(sizeof($images)==0)
			{
				echo CHtml::openTag('div', array('class'=>'review-add-images-container'));				
				echo CHtml::closeTag('div');
			}
			foreach($data->multimedias as $itemMultimedia)
			{
				if($itemMultimedia->Id_multimedia_type < 3 || $itemMultimedia->Id_document_type != null) continue;
				echo CHtml::openTag('div');
				
				echo CHtml::openTag('div',array('class'=>'index-review-single-resource'));
				switch ( $itemMultimedia->Id_multimedia_type) {
					case 4:
						echo CHtml::image('images/autocad_resource.png','',array('style'=>'width:25px;'));
						break;
					case 5:
						echo CHtml::image('images/word_resource.png','',array('style'=>'width:25px;'));
						break;
					case 6:
						echo CHtml::image('images/excel_resource.png','',array('style'=>'width:25px;'));
						break;
					case 3:
						echo CHtml::image('images/pdf_resource.png','',array('style'=>'width:25px;'));
						break;
				}
				echo CHtml::closeTag('div');
				
				echo CHtml::link(
					CHtml::encode($itemMultimedia->file_name),
					Yii::app()->baseUrl.'/docs/'.$itemMultimedia->file_name,
					array('target'=>'_blank','class'=>'review-text-docs')
				);
				echo CHtml::encode(' '.round(($itemMultimedia->size / 1024), 2));
				echo CHtml::encode(' (Kb) ');
				
				echo CHtml::openTag('div',array('class'=>'review-area-single-files-description'));
				echo CHtml::encode($itemMultimedia->description);
				echo CHtml::closeTag('div');
				
				echo CHtml::closeTag('div');
					
			}
			echo CHtml::openTag('div', array('class'=>'review-add-docs-container'));			
			echo CHtml::closeTag('div');
				
		?>
	</div>
	<?php if (User::useTechnicalDocs()):?>
	<div class="review-text-docs">
	<?php
	
		foreach($data->multimedias as $itemMultimedia)
		{
			if($itemMultimedia->Id_multimedia_type < 3 || $itemMultimedia->Id_document_type == null) continue;
			echo CHtml::openTag('div');
		
			echo CHtml::openTag('div',array('class'=>'index-review-single-resource'));
			switch ( $itemMultimedia->Id_multimedia_type) {
				case 4:
					echo CHtml::image('images/autocad_resource.png','',array('style'=>'width:25px;'));
					break;
				case 5:
					echo CHtml::image('images/word_resource.png','',array('style'=>'width:25px;'));
					break;
				case 6:
					echo CHtml::image('images/excel_resource.png','',array('style'=>'width:25px;'));
					break;
				case 3:
					echo CHtml::image('images/pdf_resource.png','',array('style'=>'width:25px;'));
					break;
			}
			echo CHtml::closeTag('div');
		
			echo CHtml::openTag('p',array('class'=>'review-text-docs check-last-doc',
														'url'=>Yii::app()->baseUrl.'/docs/'.$itemMultimedia->file_name,
														'idcustomer'=>$itemMultimedia->Id_customer, 
														'idproject'=>$itemMultimedia->album->Id_project, 
														'idmultimedia'=>$itemMultimedia->Id, 
			'iddocType'=>$itemMultimedia->Id_document_type));
			echo CHtml::encode($itemMultimedia->documentType->name);
			echo CHtml::encode(' '.round(($itemMultimedia->size / 1024), 2));
			echo CHtml::encode(' (Kb) ');
			echo CHtml::closeTag('p');
		
			echo CHtml::openTag('div',array('class'=>'review-area-single-files-description'));
			echo CHtml::encode($itemMultimedia->description);
			echo CHtml::closeTag('div');
		
			echo CHtml::closeTag('div');
				
		}
		echo CHtml::openTag('div', array('class'=>'review-add-docs-container'));		
		echo CHtml::closeTag('div');
	?>
	</div>
	<?php endif;?>
	</div>
	<div class="singles-notes-confirmations" style="display:none;">
		<?php if ($needConfirmation || $isOwner):?>
		<div class="singles-notes-confirmations-title">
			<?php 
			echo CHtml::encode("Estado de confirmaciones:");
			?>
		</div>
		<div class="singles-notes-confirmations-row">
			<?php 
				$criteria=new CDbCriteria;
				
				$criteria->addCondition('t.Id_user_group <> '. User::getCurrentUserGroup()->Id);
				$criteria->addCondition('t.Id_note = '. $data->Id);
				$criteria->addCondition('t.need_confirmation = 1');
				
				$modelUserGroupNote = UserGroupNote::model()->findAll($criteria);
				echo CHtml::openTag('div',array('class'=>'status-permission-row'));
				foreach ($modelUserGroupNote as $itemUGN)
				{
					$outOfDate = isset($itemUGN)?$itemUGN->isOutOfDate():false;
					
					echo CHtml::openTag('div',array('class'=>'review-permission-row'));
						echo CHtml::openTag('div',array('class'=>'status-permission-title'));
						echo $itemUGN->userGroup->description.":";					
						echo CHtml::closeTag('div');
						$text = "";
						$color = 'background-color:';
						$date = "";
						if($itemUGN->confirmed)
						{
							$text = CHtml::encode("Confirmado");
							$color.='#80e765;color:black;';
							$date = '('. $itemUGN->getConfirmDate() .')';
						}
						else if($itemUGN->declined)
						{
							$text = CHtml::encode("Declinado");						
							$color.='#ed5656;color:black;';
							$date = '('. $itemUGN->getConfirmDate() .')';
						}
						else if($itemUGN->isForceClose())
						{
							$text = CHtml::encode("Conf Cierre");
							$color.='#80e765;color:black;';
							$date = '('. $itemUGN->getCloseDate() .')';
						}
						else 
						{
							$text = CHtml::encode("Auto Conf");
							$color.='#80e765;color:black;';
							$date = '('. $itemUGN->getDueDate() .')';							
						}
						
						echo CHtml::openTag('div',array('class'=>'status-permission-data','style'=>$color));
						echo $text;
						echo CHtml::closeTag('div');
						
						echo CHtml::openTag('div',array('class'=>'status-permission-date'));
							echo $date;
						echo CHtml::closeTag('div');
						
					echo CHtml::closeTag('div');
				}
				echo CHtml::closeTag('div');
			?>
		</div>
		<?php endif;?>		
	</div>
	<div id="singleNoteContainer" class="singles-notes-container">
	<?php
		$notes=$data->notes;
		array_unshift($notes,$data);		
	?>
	<?php if (!empty($notes)):?>
		<?php 
		foreach($notes as $note)
		{
			$multimediasCount = count($note->multimedias);
			$class = array('class'=>'view-text-note');
			if(User::isOwnerOf($note))
			{
				$class = array('class'=>'view-text-note view-text-note-owner');
			}				
			$class['id'] = "view_text_note_".$note->Id;
				
			echo CHtml::openTag('div',$class);
				echo CHtml::openTag('div',array('class'=>'view-text-user'));
					echo CHtml::encode($note->creation_date . ' - '.$note->user->name.' '.$note->user->last_name);
				echo CHtml::closeTag('div');
				
				
				echo CHtml::openTag('div',array('class'=>'view-text-note-actions'));
				if($multimediasCount > 0)
				{
					echo CHtml::image('images/attch.png','',
							array('id'=>'attch-left-note_'.$note->Id.'_'.$data->Id, 'class'=>'action-show-hide-attch', 'title'=>'Adjunto', 'style'=>'width:25px;top:-5px;'));
				}
				echo CHtml::closeTag('div');
				echo CHtml::openTag('div',array('class'=>'mini-note-attch-zone'));
								
				/***************************IMAGEN*********************************************/
				echo CHtml::openTag('div',array('class'=>'mini-note-images'));
				$images = array();
				$height=0;
				foreach($note->multimedias as $multimedia)
				{
					if($multimedia->Id_multimedia_type!=1) continue;
					$image= array();
					$image['image'] = "images/".$multimedia->file_name;
					$image['small_image'] = "images/".$multimedia->file_name_small;
					$image['caption'] = $multimedia->description;
					$images[]=$image;
				}
				if(sizeof($images)>0)
				{
					$this->widget('ext.photoswipe.photoswipe', array(
							'images'=>$images,
							'Id'=>$note->Id,
					));
				}
				echo CHtml::closeTag('div');
				/***************************DOCUMENT*********************************************/
				echo CHtml::openTag('div',array('class'=>'mini-note-docs'));
				foreach($note->multimedias as $item)
				{
					if($item->Id_multimedia_type < 3 || $item->Id_document_type != null) continue;
					echo CHtml::openTag('div');
						
					echo CHtml::openTag('div',array('class'=>'index-review-single-resource'));
					switch ( $item->Id_multimedia_type) {
						case 4:
							echo CHtml::image('images/autocad_resource.png','',array('style'=>'width:25px;'));
							break;
						case 5:
							echo CHtml::image('images/word_resource.png','',array('style'=>'width:25px;'));
							break;
						case 6:
							echo CHtml::image('images/excel_resource.png','',array('style'=>'width:25px;'));
							break;
						case 3:
							echo CHtml::image('images/pdf_resource.png','',array('style'=>'width:25px;'));
							break;
					}
					echo CHtml::closeTag('div');
						
					echo CHtml::link(
							CHtml::encode($item->file_name),
							Yii::app()->baseUrl.'/docs/'.$item->file_name,
							array('target'=>'_blank','class'=>'review-text-docs')
					);
					echo CHtml::encode(' '.round(($item->size / 1024), 2));
					echo CHtml::encode(' (Kb) ');
						
					echo CHtml::openTag('div',array('class'=>'review-area-single-files-description'));
					echo CHtml::encode($item->description);
					echo CHtml::closeTag('div');
						
					echo CHtml::closeTag('div');
				
				}
				foreach($note->multimedias as $multimedia)
				{
					if($multimedia->Id_multimedia_type < 3 || $multimedia->Id_document_type == null) continue;
					echo CHtml::openTag('div');
				
					echo CHtml::openTag('div',array('class'=>'index-review-single-resource'));
					switch ( $multimedia->Id_multimedia_type) {
						case 4:
							echo CHtml::image('images/autocad_resource.png','',array('style'=>'width:25px;'));
							break;
						case 5:
							echo CHtml::image('images/word_resource.png','',array('style'=>'width:25px;'));
							break;
						case 6:
							echo CHtml::image('images/excel_resource.png','',array('style'=>'width:25px;'));
							break;
						case 3:
							echo CHtml::image('images/pdf_resource.png','',array('style'=>'width:25px;'));
							break;
					}
					echo CHtml::closeTag('div');
				
					echo CHtml::openTag('p',array('class'=>'review-text-docs check-last-doc',
							'url'=>Yii::app()->baseUrl.'/docs/'.$multimedia->file_name,
							'idcustomer'=>$multimedia->Id_customer,
							'idproject'=>$multimedia->Id_project,
							'idmultimedia'=>$multimedia->Id,
							'iddocType'=>$multimedia->Id_document_type));
					echo CHtml::encode($multimedia->documentType->name);
					echo CHtml::encode(' '.round(($multimedia->size / 1024), 2));
					echo CHtml::encode(' (Kb) ');
					echo CHtml::closeTag('p');
				
				
					echo CHtml::openTag('div',array('class'=>'review-area-single-files-description'));
					echo CHtml::encode($multimedia->description);
					echo CHtml::closeTag('div');
				
					echo CHtml::closeTag('div');
				
				}
				echo CHtml::closeTag('div');
				echo CHtml::closeTag('div');
				
				
				
				
				
				echo CHtml::openTag('p',array('class'=>'single-formated-text'));
					echo $note->note;
				echo CHtml::closeTag('p');
			echo CHtml::closeTag('div');
		}
		?>		
	<?php endif?>
	</div>
</div>


