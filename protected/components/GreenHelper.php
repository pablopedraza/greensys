<?php
class GreenHelper
{
	static public function saveLinks($links, $id,$idEntityType,$idKey)
	{
		if(isset($links))
		{
			foreach ($links as $link){
				$hyperlink = new Hyperlink;
				$hyperlink->attributes = array(
												'description'=>$link,
												'Id_entity_type'=>$idEntityType,
												$idKey=>$id);
		
				$hyperlink->save();
			}
		}
	}

	/**
	 * 
	 * Get data value (default type = string)
	 * @param array() $data
	 * @param string $field
	 * @param array() $arrFields
	 * @param string $type (boolean, int, string)
	 */
	private function getDataValue($data, $field, $arrFields, $type = 'string')
	{
		$returnValue = null;
		
		$value = $data[array_search($field, $arrFields)];
		switch ($type) {
			case "boolean":
				$returnValue =  ($value == 'True')?1:0;
				break;
			case "int":
				$returnValue =  (!empty($value))?(int)$value:0;
				break;
			case "decimal":
				$returnValue =  (!empty($value))?(float)$value:0.00;
				break;
			case "string":
				$returnValue =  (!empty($value))?$value:'';
				break;
		}
		
		return $returnValue;
	}
	
	private function cellColor($sheet, $cells, $color)
	{
		$sheet->getStyle($cells)->getFill()
		->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
									        'startcolor' => array('rgb' => $color)
		));
	}
	
	static public function exportBudgetToExcel($idBudget, $versionNumber)
	{
		
		Yii::import('ext.phpexcel.XPHPExcel');
		$objPHPExcel= XPHPExcel::createPHPExcel();
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
		->setLastModifiedBy("Maarten Balliauw")
		->setTitle("Office 2007 XLSX Test Document")
		->setSubject("Office 2007 XLSX Test Document")
		->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		->setKeywords("office 2007 openxml php")
		->setCategory("Test result file");
		
		//INDICES EXCEL
		$indexService = array('name'=>'A', 'description'=>'B');
		$indexProduct = array('model'=>'A','description'=>'B','image'=>'C',
										'quantity'=>'F','price'=>'G','discount'=>'H','total'=>'I');
		$indexExtra	  = array('descriptionStart'=>'A', 'descriptionEnd'=>'E', 'quantity'=>'F', 'price'=>'G',
										'discount'=>'H','total'=>'I');
		$indexTotal	  = array('descriptionStart'=>'F','descriptionEnd'=>'H','total'=>'I');
		
		$style_border = array(
		       'borders' => array(
		             'outline' => array(
		                    'style' => PHPExcel_Style_Border::BORDER_THIN,
		                    'color' => array('argb' => '00000000'),
							),
				),
		);		
		
		$style_num = array(
		                'alignment' => array(
		                    		'wrap' => true,
		                                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
									),
		);
		
		//sheet 0
		$sheet = $objPHPExcel->setActiveSheetIndex(0);
		$sheet->getColumnDimension($indexService['description'])->setWidth(50);
		$row = 1;
		
		$criteria = new CDbCriteria();
		$criteria->addCondition('Id_budget = '.$idBudget);
		$criteria->addCondition('version_number = '.$versionNumber);
		$criteria->group = 'Id_service';
		$budgetItemServices = BudgetItem::model()->findAll($criteria);
		
		
		foreach($budgetItemServices as $budgetItemService)
		{
			
			//SERVICE---------------------------------------------------------------
			$serviceName = 'General';
			$serviceDesc = 'Items sin area designada';
			if(isset($budgetItemService->service))
			{
				$serviceName = $budgetItemService->service->description;
				$serviceDesc = $budgetItemService->service->long_description;
				
				$projectServiceDB = ProjectService::model()->findByAttributes(array('Id_project'=>$budgetItemService->budget->Id_project,
																	'Id_service'=>$budgetItemService->Id_service));
				if(isset($projectServiceDB))
					$serviceDesc = $projectServiceDB->long_description;				
			}
			
			$sheet->getStyle($indexService['description'].$row)->getAlignment()->setWrapText(true);
			
			$sheet->setCellValue($indexService['name'].$row, $serviceName);
			$sheet->setCellValue($indexService['description'].$row, $serviceDesc);

			self::cellColor($sheet, $indexService['name'].$row.':'.$indexService['description'].$row, 'e6e6fa');
			$sheet->getStyle($indexService['name'].$row.':'.$indexService['description'].$row)->applyFromArray($style_border);
			
			$row++;
			//END SERVICE---------------------------------------------------------------
						
			//HEADER BUDGET ITEM---------------------------------------------------------------
			$sheet->setCellValue($indexProduct['model'].$row, 'Modelo');
			$sheet->setCellValue($indexProduct['description'].$row, 'Descripcion');
			$sheet->setCellValue($indexProduct['image'].$row, 'Imagen');
			$sheet->setCellValue($indexProduct['quantity'].$row, 'Cantidad');
			$sheet->setCellValue($indexProduct['price'].$row, 'Precio');
			$sheet->setCellValue($indexProduct['discount'].$row, 'Descuento');
			$sheet->setCellValue($indexProduct['total'].$row, 'Total');
							
			self::cellColor($sheet, $indexProduct['model'].$row.':'.$indexProduct['total'].$row, '2c86ff');
			$sheet->getStyle($indexProduct['model'].$row.':'.$indexProduct['total'].$row)->applyFromArray($style_border);			
			//END HEADER BUDGET ITEM---------------------------------------------------------------
			
			//BODY BUDGET ITEM---------------------------------------------------------------
			$criteria = new CDbCriteria();
			$criteria->addCondition('Id_budget = '.$idBudget);
			$criteria->addCondition('version_number = '.$versionNumber);
			
			$serviceCondition = '';
			if(isset($budgetItemService->Id_service))
			{
				$serviceCondition = 'Id_service = '.$budgetItemService->Id_service. ' OR 
												(Id_budget_item in (select Id from budget_item bi 	
												where Id_budget = '.$idBudget .' 
												and version_number = '.$versionNumber .'
												and Id_product is not null
												and Id_service = '.$budgetItemService->Id_service.' )
											AND is_included = 1)';
			}
			else
				$serviceCondition = '(Id_service is null and Id_budget_item is null)';
			
			$criteria->addCondition($serviceCondition);
			$criteria->addCondition('Id_product is not null');
				
			$budgetItems = BudgetItem::model()->findAll($criteria);
			$row++;
			foreach($budgetItems as $budgetItem)
			{
				$sheet->setCellValue($indexProduct['model'].$row, $budgetItem->product->model);
				$sheet->setCellValue($indexProduct['description'].$row, $budgetItem->product->short_description);
				$sheet->getStyle($indexProduct['description'].$row)->getAlignment()->setWrapText(true);
				
				$criteria = new CDbCriteria();
				$criteria->join = 'inner join product_multimedia pm on (pm.Id_multimedia = t.Id)';
				$criteria->addCondition('t.Id_multimedia_type = 1');
				$criteria->addCondition('pm.Id_product = '. $budgetItem->Id_product);
				
				$modelMultimediaDB = Multimedia::model()->find($criteria);
				$sumImageRows = 0;
				if(isset($modelMultimediaDB))
				{				
					$objDrawingPType = new PHPExcel_Worksheet_Drawing();
					$objDrawingPType->setWorksheet($sheet);
					$objDrawingPType->setName("Pareto By Type");
					$objDrawingPType->setPath(Yii::app()->basePath.DIRECTORY_SEPARATOR."../images/". $modelMultimediaDB->file_name_small);
					$objDrawingPType->setCoordinates($indexProduct['image'].$row);
					$objDrawingPType->setOffsetX(1);
					$objDrawingPType->setOffsetY(1);
					$objDrawingPType->setHeight(95);
					$sumImageRows = 4;
				}
				
				$sheet->setCellValue($indexProduct['quantity'].$row, $budgetItem->quantity);
				$sheet->setCellValue($indexProduct['price'].$row, $budgetItem->price);
				$sheet->setCellValue($indexProduct['discount'].$row, $budgetItem->getDiscountType(). $budgetItem->getDiscount());
				$sheet->setCellValue($indexProduct['total'].$row, $budgetItem->getTotalPriceWOChildern());
				
				$sheet->getStyle($indexProduct['quantity'].$row.':'.$indexProduct['total'].$row)->applyFromArray($style_num);
				
				$newRow = $row + $sumImageRows;
				$sheet->getStyle($indexProduct['model'].$row.':'.$indexProduct['total'].$newRow)->applyFromArray($style_border);
				
				$row++;
				$row = $row + $sumImageRows;
			}
				
			$row++;
		}
		//END BODY BUDGET ITEM---------------------------------------------------------------
		
		//EXTRAS---------------------------------------------------------------
		$criteria = new CDbCriteria();
		$criteria->addCondition('Id_budget = '.$idBudget);
		$criteria->addCondition('version_number = '.$versionNumber);
		$criteria->addCondition('Id_service is null');
		$criteria->addCondition('Id_product is null');
			
		$budgetItems = BudgetItem::model()->findAll($criteria);
		
		if(count($budgetItems)>0)
		{
			//SERVICE EXTRAS---------------------------------------------------------------
			$row++;
			$sheet->setCellValue($indexService['name'].$row, 'Extras');
			$sheet->setCellValue($indexService['description'].$row, 'Agregados');
			self::cellColor($sheet, $indexService['name'].$row.':'.$indexService['description'].$row, 'e6e6fa');
			$sheet->getStyle($indexService['name'].$row.':'.$indexService['description'].$row)->applyFromArray($style_border);
			$row++;
			//END SERVICE EXTRAS---------------------------------------------------------------
			
			//HEADER EXTRAS---------------------------------------------------------------
			$sheet->setCellValue($indexExtra['descriptionStart'].$row, 'Descripcion');
			$sheet->setCellValue($indexExtra['quantity'].$row, 'Cantidad');
			$sheet->setCellValue($indexExtra['price'].$row, 'Precio');
			$sheet->setCellValue($indexExtra['discount'].$row, 'Descuento');
			$sheet->setCellValue($indexExtra['total'].$row, 'Total');
			
			$sheet->mergeCells($indexExtra['descriptionStart'].$row.':'.$indexExtra['descriptionEnd'].$row);
				
			self::cellColor($sheet, $indexExtra['descriptionStart'].$row.':'.$indexExtra['total'].$row, '2c86ff');
			$sheet->getStyle($indexExtra['descriptionStart'].$row.':'.$indexExtra['total'].$row)->applyFromArray($style_border);
			$row++;
			//END HEADER EXTRAS---------------------------------------------------------------
			
			//BODY EXTRAS---------------------------------------------------------------
			foreach($budgetItems as $budgetItem)
			{
				$sheet->mergeCells($indexExtra['descriptionStart'].$row.':'.$indexExtra['descriptionEnd'].$row);
				$sheet->setCellValue($indexExtra['descriptionStart'].$row, $budgetItem->description);
				$sheet->getStyle($indexExtra['descriptionStart'].$row)->getAlignment()->setWrapText(true);
				$sheet->setCellValue($indexExtra['quantity'].$row, $budgetItem->quantity);
				$sheet->setCellValue($indexExtra['price'].$row, $budgetItem->price);
				$sheet->setCellValue($indexExtra['discount'].$row, $budgetItem->getDiscountType(). $budgetItem->discount);
				$sheet->setCellValue($indexExtra['total'].$row, $budgetItem->getTotalPriceWOChildern());
				$sheet->getStyle($indexExtra['descriptionStart'].$row.':'.$indexExtra['total'].$row)->applyFromArray($style_border);
				
				$sheet->getStyle($indexExtra['quantity'].$row.':'.$indexExtra['total'].$row)->applyFromArray($style_num);
				$row++;
			}
			//END BODY EXTRAS---------------------------------------------------------------
		}
		//END EXTRAS---------------------------------------------------------------
		
		
		//TOTALES---------------------------------------------------------------
		$row++;
		$modelBudget = Budget::model()->findByAttributes(array('Id'=>$idBudget,'version_number'=>$versionNumber));
		if(isset($modelBudget))
		{
			//sub total
			$sheet->setCellValue($indexTotal['descriptionStart'].$row, 'Sub Total');
			$sheet->mergeCells($indexTotal['descriptionStart'].$row.':'.$indexTotal['descriptionEnd'].$row);
			self::cellColor($sheet, $indexTotal['descriptionStart'].$row.':'.$indexTotal['descriptionStart'].$row, 'e6e6fa');
			$sheet->getStyle($indexTotal['total'].$row)->applyFromArray($style_num);
			$sheet->getStyle($indexTotal['descriptionStart'].$row.':'.$indexTotal['total'].$row)->applyFromArray($style_border);
			$sheet->setCellValue($indexTotal['total'].$row, $modelBudget->totalPrice);
			$row++;
			
			//sub total
			$sheet->setCellValue($indexTotal['descriptionStart'].$row, 'Descuento');
			$sheet->mergeCells($indexTotal['descriptionStart'].$row.':'.$indexTotal['descriptionEnd'].$row);
			self::cellColor($sheet, $indexTotal['descriptionStart'].$row.':'.$indexTotal['descriptionStart'].$row, 'e6e6fa');
			$sheet->getStyle($indexTotal['total'].$row)->applyFromArray($style_num);
			$sheet->getStyle($indexTotal['descriptionStart'].$row.':'.$indexTotal['total'].$row)->applyFromArray($style_border);			
			$sheet->setCellValue($indexTotal['total'].$row, $modelBudget->TotalDiscount);
			$row++;
			
			//sub total
			$sheet->setCellValue($indexTotal['descriptionStart'].$row, 'Total');
			$sheet->mergeCells($indexTotal['descriptionStart'].$row.':'.$indexTotal['descriptionEnd'].$row);
			self::cellColor($sheet, $indexTotal['descriptionStart'].$row.':'.$indexTotal['descriptionStart'].$row, 'e6e6fa');
			$sheet->getStyle($indexTotal['total'].$row)->applyFromArray($style_num);
			$sheet->getStyle($indexTotal['descriptionStart'].$row.':'.$indexTotal['total'].$row)->applyFromArray($style_border);
			$sheet->setCellValue($indexTotal['total'].$row, $modelBudget->TotalPriceWithDiscount);
			$row++;
			
		}
		//END TOTALES---------------------------------------------------------------
		
		//set column auto-size		
		foreach(range($indexProduct['quantity'],$indexProduct['total']) as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
			->setAutoSize(true);
		}
		
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Simple');
		
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		
		// 		$objDrawingPType = new PHPExcel_Worksheet_Drawing();
		// 		$objDrawingPType->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
		// 		$objDrawingPType->setName("Pareto By Type");
		// 		//$objDrawingPType->setPath(Yii::app()->basePath.DIRECTORY_SEPARATOR."../images/519538c3763c2.jpg");
		// 		$objDrawingPType->setCoordinates('C5');
		// 		$objDrawingPType->setOffsetX(0);
		// 		$objDrawingPType->setOffsetY(0);
		// 		$objDrawingPType->setWidth(200);
		
		// Redirect output to a client web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="01simple.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		Yii::app()->end();
	}
	
	static public function importMeasuresFromExcel($modelUpload, $modelMeasureImportLog)
	{		
		$Id_linear = $modelMeasureImportLog->Id_measurement_unit_linear;
		$Id_weight =  $modelMeasureImportLog->Id_measurement_unit_weight;
		
		$file=CUploadedFile::getInstance($modelUpload,'file');
		$sheet_array = Yii::app()->yexcel->readActiveSheet($file->tempName);
		
		$ext = end(explode(".", $file->name));
		$ext = strtolower($ext);
		
		$uniqueId = uniqid();

		$folder = "docs/";
		$fileName = $uniqueId.'.'.$ext;
		$filePath = $folder . $fileName;
		
		//save doc
		move_uploaded_file($file->tempName,$filePath);
		
		$arrCols = array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',7=>'G');
		$col_model =		'';
		$col_weight =		'';
		$col_length =		'';
		$col_width =		'';
		$col_height =		'';
		$col_qty = 			'';
		$col_index = 0;
		
		foreach( $sheet_array[1] as $header ) 
		{			
			$colName = strtoupper($header);
			$col_index++;
			if(strpos($colName, 'MODEL')!== false)
			{
				$col_model = $arrCols[$col_index];
				continue; 
			}			
			if(strpos($colName, 'QTY')!== false)
			{
				$col_qty = $arrCols[$col_index];
				continue;
			}
			if(strpos($colName, 'WEIGHT')!== false)
			{
				$col_weight = $arrCols[$col_index];
				continue;
			}
			if(strpos($colName, 'LENGTH')!== false)
			{
				$col_length = $arrCols[$col_index];
				continue;
			}
			if(strpos($colName, 'WIDTH')!== false)
			{
				$col_width = $arrCols[$col_index];
				continue;
			}
			if(strpos($colName, 'HEIGHT')!== false)
			{
				$col_height = $arrCols[$col_index];
				continue;
			}			
		}
			
		$row_index = 1;
		$model_not_found = '';
		foreach( $sheet_array as $row ) 
		{
			if($row_index != 1)
			{
				$criteria = new CDbCriteria();
				$criteria->addCondition('t.Id_brand = '. $modelMeasureImportLog->Id_brand);
				$newModel = str_replace('"','',$row[$col_model]);
				
				if(empty($newModel))
					continue;
				
				$criteria->addCondition('"'. $newModel . '" like CONCAT("%", model ,"%")');
				
				$modelProductDB = Product::model()->find($criteria);
				
				if(isset($modelProductDB))
				{	
					$transaction = $modelProductDB->dbConnection->beginTransaction();
					try {						
						$modelProductDB->length = (float)$row[$col_length];
						$modelProductDB->width = (float)$row[$col_width];
						$modelProductDB->height = (float)$row[$col_height];
						$modelProductDB->weight = (float)$row[$col_weight];					
						$modelProductDB->Id_measurement_unit_linear = $Id_linear;
						$modelProductDB->Id_measurement_unit_weight = $Id_weight;
						$modelProductDB->save();
						
						$qty = isset($row[$col_qty])?(int)$row[$col_qty]:0;
						if($qty > 1)
						{
							$modelPackagingDB = Packaging::model()->findByAttributes(array('qty'=>$qty,
																				'Id_product'=>$modelProductDB->Id,
																		));
							if(!isset($modelPackagingDB))
							{
								$modelPackagingDB = new Packaging();
								$modelPackagingDB->Id_product = $modelProductDB->Id;
								$modelPackagingDB->qty = $qty;
								$modelPackagingDB->save();
							}
						}
						
						$transaction->commit();
					} catch (Exception $e) {
						$transaction->rollback();
					}		
					
				}
				else
				{ 
					$modelProduct = new Product();
					$modelProduct->model = $row[$col_model];
					$modelProduct->length = (float)$row[$col_length];
					$modelProduct->width = (float)$row[$col_width];
					$modelProduct->height = (float)$row[$col_height];
					$modelProduct->weight = (float)$row[$col_weight];
					$modelProduct->Id_measurement_unit_linear = $Id_linear;
					$modelProduct->Id_measurement_unit_weight = $Id_weight;
					$modelProduct->Id_brand = $modelMeasureImportLog->Id_brand;
					$modelProduct = self::setEmptyProduct($modelProduct);
					$modelProduct->save();
					$model_not_found .= $row[$col_model]. ', ';
				}
			}
			$row_index++;			
		}

		$modelMeasureImportLog->file_name = $fileName;
		$modelMeasureImportLog->original_file_name = $file->name;		
		$modelMeasureImportLog->not_found_model = rtrim($model_not_found, ", ");
		$modelMeasureImportLog->save();
	}
	
	static public function importPurchListFromExcel($modelUpload, $modelPriceList)
	{
		$file=CUploadedFile::getInstance($modelUpload,'file');
		$sheet_array = Yii::app()->yexcel->readActiveSheet($file->tempName);
	
		$ext = end(explode(".", $file->name));
		$ext = strtolower($ext);
	
		$uniqueId = uniqid();		
	
		$folder = "docs/";
		$fileName = $uniqueId.'.'.$ext;
		$filePath = $folder . $fileName;
		
		//save doc
		move_uploaded_file($file->tempName,$filePath);
	
		$arrCols = array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F');
		$col_model =	'';
		$col_dealer =	'';
		$col_msrp =		'';
		$col_part_num = '';		
		$col_index = 0;
	
		foreach( $sheet_array[1] as $header )
		{
			$colName = strtoupper($header);
			$col_index++;
			if(strpos($colName, 'MODEL')!== false)
			{
				$col_model = $arrCols[$col_index];
				continue;
			}
			if(strpos($colName, 'DEALER')!== false)
			{
				$col_dealer = $arrCols[$col_index];
				continue;
			}
			if(strpos($colName, 'PART')!== false)
			{
				$col_part_num = $arrCols[$col_index];
				continue;
			}
			if(strpos($colName, 'MSRP')!== false || strpos($colName, 'MAP')!== false)
			{
				$col_msrp = $arrCols[$col_index];
				continue;
			}
		}
			
		//save purch list price to add items
		if($modelPriceList->save())
		{		
			$row_index = 1;
			$model_not_found = '';
			foreach( $sheet_array as $row )
			{
				if($row_index != 1)
				{	

					$newModel = str_replace('"','',$row[$col_model]);
					
					if(empty($newModel))
						continue;
					
					$modelProductDB = Product::model()->findByAttributes(array('model'=>$newModel,
																			'Id_supplier'=>$modelPriceList->Id_supplier));
					
					$msrp = (float)str_replace('$','',$row[$col_msrp]);
					$dealer_cost = (float)str_replace('$','',$row[$col_dealer]);
					$profit_rate = 0;
					
					if($dealer_cost != 0)
						$profit_rate = $msrp / $dealer_cost;
					
					if(isset($modelProductDB))
					{
						
						$modelPriceListItem = new PriceListItem();
						$modelPriceListItem->Id_product = $modelProductDB->Id;
						$modelPriceListItem->Id_price_list = $modelPriceList->Id;
						$modelPriceListItem->msrp = $msrp;
						$modelPriceListItem->dealer_cost = $dealer_cost;
						$modelPriceListItem->profit_rate = (float)$profit_rate;
						$modelPriceListItem->save();
					}
					else
					{
						$modelProduct = new Product();
						$transaction = $modelProduct->dbConnection->beginTransaction();
						try 
						{
							$modelProduct->model = $row[$col_model];
							$modelProduct->part_number = isset($row[$col_part_num])?$row[$col_part_num]:"";
							$modelProduct->Id_supplier = $modelPriceList->Id_supplier;
							$modelProduct = self::setEmptyProduct($modelProduct);
							$modelProduct->save();
							
							$modelPriceListItem = new PriceListItem();
							$modelPriceListItem->Id_product = $modelProduct->Id;
							$modelPriceListItem->Id_price_list = $modelPriceList->Id;
							$modelPriceListItem->msrp = $msrp;
							$modelPriceListItem->dealer_cost = $dealer_cost;
							$modelPriceListItem->profit_rate = (float)$profit_rate;
							$modelPriceListItem->save();
							$transaction->commit();
						} 
						catch (Exception $e) 
						{
							$transaction->rollback();
						}
						
						$model_not_found .= $row[$col_model]. ', ';
					}
				}
				$row_index++;
			}
		
			$modelPriceListPuchImportLog = new PriceListPurchImportLog();
			$modelPriceListPuchImportLog->Id_price_list = $modelPriceList->Id;
			$modelPriceListPuchImportLog->file_name = $fileName;
			$modelPriceListPuchImportLog->original_file_name = $file->name;
			$modelPriceListPuchImportLog->Id_supplier = $modelPriceList->Id_supplier;
			$modelPriceListPuchImportLog->not_found_model = rtrim($model_not_found, ", ");
			$modelPriceListPuchImportLog->save();
		}
	}
	
	/**
	 * 
	 * Imports a csv file to database
	 * @param UploadCsv $modelUpload
	 * @return int Id from ImportLog
	 */
	static public function importFromExcel($modelUpload)
	{	
		$importLogId = null;	
		$successRows = "";
		$errorRows = "";
		$existRows = "";		
		$fileName = "";
		
		$file=CUploadedFile::getInstance($modelUpload,'file');
		
		$fileName = $file->name;
		$importCode = uniqid();
		$handle = fopen($file->tempName, "r");
		if ($handle) {
			$row = 2; //porque el 1 contiene el nombre de los campos
			$firstLine = fgets($handle, 4096);
			$arrFields=  explode( ',',$firstLine);
			
			while (($data = fgetcsv($handle, 4096, ",")) !== FALSE)
			{

				$modelField = self::getDataValue($data, '"Model"', $arrFields);
				$manufacturer = self::getDataValue($data, '"Manufacturer"', $arrFields);
				
				$criteria = new CDbCriteria();
				$criteria->with[]='brand';
				$criteria->addCondition("brand.description = '". $manufacturer."'");
				$criteria->addCondition("t.model = '". $modelField."'");
				
				$modelProduct = Product::model()->find($criteria);
				$modelNewProduct = self::setProduct($data, $arrFields, $importCode);
								
				if(isset($modelNewProduct))
				{
					if(isset($modelProduct))//already exists in DB
					{
						
						$differences = false;
						foreach($modelNewProduct->attributes as $key => $value) {
							if(strstr($key,'Id_') == false &&
							   $key != 'date_creation' &&
							   $key != 'import_code' &&
							   $key != 'code' &&
							   $key != 'Id')
							{
								if($modelProduct->$key != $modelNewProduct->$key)			
									$differences = true;
							}
						}
						
						Product::model()->deleteAllByAttributes(array('Id_product'=>$modelProduct->Id));
						$modelNewProduct->Id_product = $modelProduct->Id;
						
						if(!$differences)
						{							
							$existRows = $existRows . $row. ', ';
							$row++;
							continue;
						}
					}
					
					$transaction = $modelNewProduct->dbConnection->beginTransaction();
					try {
				
						if($modelNewProduct->save())
						{
							$transaction->commit();
							$successRows = $successRows . $row. ', ';
						}
						else
						{
							$transaction->rollback();
							$errorRows = $errorRows . $row. ', ';
						}
				
					} catch (Exception $e) {
						$transaction->rollback();
						$errorRows = $errorRows . $row. ', ';
					}
				}
				else
					$errorRows = $errorRows . $row. ', ';				
				
				$row++;
			} //end while
			
			fclose($handle);
			
			$modelImportLog = new ImportLog();
			$modelImportLog->file_name = $fileName;
			$modelImportLog->already_exist_rows = rtrim($existRows, ", ");
			$modelImportLog->insert_rows = rtrim($successRows, ", ");
			$modelImportLog->error_rows = rtrim($errorRows, ", ");
			$modelImportLog->import_code = $importCode;
			$modelImportLog->total_rows = $row - 1;
			$modelImportLog->save();
			
			$importLogId = $modelImportLog->Id;
		} //end if(handle)
		
		return $importLogId;
	}
	
	private function setEmptyProduct($modelProduct)
	{	
		//BEGIN NOMENCLATOR-------------------------------------------------
		if(!isset($modelProduct->Id_nomenclator))
		{
			$modelNomenclator = Nomenclator::model()->findByAttributes(array('description'=>'Dtools'));
			if(!isset($modelNomenclator))
			{
				$modelNomenclator = new Nomenclator();
				$modelNomenclator->description = 'Dtools';
				$modelNomenclator->save();
			}
			$modelProduct->Id_nomenclator = $modelNomenclator->Id;
		}
		//END NOMENCLATOR-------------------------------------------------
		
		
		//BEGIN MEASURE UNIT WEIGHT-------------------------------------------------
		if(!isset($modelProduct->Id_measurement_unit_weight))
		{
			$modelMeasureUnitWeight = MeasurementUnit::model()->findByAttributes(array('short_description'=>'kg'));
			if(!isset($modelMeasureUnitWeight))
			{
				$modelMeasureType = MeasurementType::model()->findByAttributes(array('description'=>'weight'));
				if(!isset($modelMeasureType))
				{
					$modelMeasureType->description = 'weight';
					$modelMeasureType->save();
				}
					
				$modelMeasureUnitWeight = new MeasurementUnit();
				$modelMeasureUnitWeight->Id_measurement_type = $modelMeasureType->Id;
				$modelMeasureUnitWeight->short_description = 'kg';
				$modelMeasureUnitWeight->description = 'kilograms';
				$modelMeasureUnitWeight->save();
					
			}
			$modelProduct->Id_measurement_unit_weight = $modelMeasureUnitWeight->Id;
		}
		//END MEASURE UNIT WEIGHT-------------------------------------------------
			
		//BEGIN MEASURE UNIT LINEAR-------------------------------------------------
		if(!isset($modelProduct->Id_measurement_unit_linear))
		{
			$modelMeasureUnitLinear = MeasurementUnit::model()->findByAttributes(array('short_description'=>'mm'));
			if(!isset($modelMeasureUnitLinear))
			{
				$modelMeasureType = MeasurementType::model()->findByAttributes(array('description'=>'linear'));
				if(!isset($modelMeasureType))
				{
					$modelMeasureType->description = 'linear';
					$modelMeasureType->save();
				}
					
				$modelMeasureUnitLinear = new MeasurementUnit();
				$modelMeasureUnitLinear->Id_measurement_type = $modelMeasureType->Id;
				$modelMeasureUnitLinear->short_description = 'mm';
				$modelMeasureUnitLinear->description = 'Milimeters';
				$modelMeasureUnitLinear->save();
					
			}
			$modelProduct->Id_measurement_unit_linear = $modelMeasureUnitLinear->Id;
		}
		//END MEASURE UNIT LINEAR-------------------------------------------------
		
		//BEGIN BRAND-------------------------------------------------
		if(!isset($modelProduct->Id_brand))
		{
			$manufacturer = "--";
			$modelBrand = Brand::model()->findByAttributes(array('description'=>$manufacturer));
			if(!isset($modelBrand))
			{
				$modelBrand = new Brand();
				$modelBrand->description = $manufacturer;
				$modelBrand->save();
			}
			$modelProduct->Id_brand = $modelBrand->Id;
		}
		//END BRAND-------------------------------------------------
		
		//BEGIN VOLTS-------------------------------------------------
		$volts = 0;
		$modelVolts = Volts::model()->findByAttributes(array('volts'=>$volts));
		if(!isset($modelVolts))
		{
			$modelVolts = new Volts();
			$modelVolts->volts = $volts;
			$modelVolts->save();
		}
		$modelProduct->Id_volts = $modelVolts->Id;
		//END VOLTS-------------------------------------------------
		
		//BEGIN CATEGORY-------------------------------------------------
		$category = "--";
		$modelCategory = Category::model()->findByAttributes(array('description'=>$category));
		if(!isset($modelCategory))
		{
			$modelCategory = new Category();
			$modelCategory->description = $category;
			$modelCategory->save();
		}
		$modelProduct->Id_category = $modelCategory->Id;
		//END CATEGORY-------------------------------------------------
		
		//BEGIN SUB-CATEGORY-------------------------------------------------
		$subCategory = "--";
		$modelSubCategory = SubCategory::model()->findByAttributes(array('description'=>$subCategory));
		if(!isset($modelSubCategory))
		{
			$modelSubCategory = new SubCategory();
			$modelSubCategory->description = $subCategory;
			$modelSubCategory->save();
		}
		$modelProduct->Id_sub_category = $modelSubCategory->Id;
		//END SUB-CATEGORY-------------------------------------------------
		
		//BEGIN PRODUCT-TYPE-------------------------------------------------
		$productType = "--";
		$modelProductType = ProductType::model()->findByAttributes(array('description'=>$productType));
		if(!isset($modelProductType))
		{
			$modelProductType = new ProductType();
			$modelProductType->description = $productType;
			$modelProductType->save();
		}
		$modelProduct->Id_product_type = $modelProductType->Id;
		//END PRODUCT-TYPE-------------------------------------------------
		
		//BEGIN SUPPLIER-------------------------------------------------
		if(!isset($modelProduct->Id_supplier))
		{
			$modelSupplier = Supplier::model()->findByAttributes(array('business_name'=>'--'));
			if(!isset($modelSupplier))
			{
				$modelContact = new Contact();
				$modelContact->description = '--';
				$modelContact->telephone_1 = '--';
				$modelContact->email = uniqid().'@bb.com';
				$modelContact->save();
					
				$modelSupplier = new Supplier();
				$modelSupplier->business_name = '--';
				$modelSupplier->Id_contact = $modelContact->Id;
				$modelSupplier->save();
			}
			$modelProduct->Id_supplier = $modelSupplier->Id;
		}
		//END SUPPLIER-------------------------------------------------
		
		$modelProduct->from_dtools = 0;
		$modelProduct->hide = 0;			
					
		return $modelProduct; 
	}
	
	private function setProduct($data, $arrFields, $importCode)
	{
		$modelProduct = new Product();
		$transaction = $modelProduct->dbConnection->beginTransaction(); 
		try {
					
			$modelProduct->discontinued = self::getDataValue($data, '"Discontinued"', $arrFields, 'boolean');
			$modelProduct->height = self::getDataValue($data, '"Height"', $arrFields,'decimal');
			$modelProduct->width = self::getDataValue($data, '"Width"', $arrFields,'decimal');
			$modelProduct->length = self::getDataValue($data, '"Depth"', $arrFields,'decimal');
			$modelProduct->weight = self::getDataValue($data, '"Weight"', $arrFields,'decimal');
			$modelProduct->msrp = self::getDataValue($data, '"MSRP"', $arrFields, 'decimal');
			$modelProduct->time_instalation = self::getDataValue($data, '"Labor Hours"', $arrFields);
			$modelProduct->unit_rack = self::getDataValue($data, '"Rack Units"', $arrFields,'int');
			$modelProduct->need_rack = ($modelProduct->unit_rack>0)?1:0;
			$modelProduct->current = self::getDataValue($data, '"Amps"', $arrFields,'int');
			$modelProduct->power = self::getDataValue($data, '"Watts"', $arrFields,'int');
			
			$modelProduct->model = self::getDataValue($data, '"Model"', $arrFields);
			$modelProduct->vendor = self::getDataValue($data, '"Vendor"', $arrFields);
			$modelProduct->long_description =  self::getDataValue($data, '"Long Description"', $arrFields);
			$modelProduct->short_description = self::getDataValue($data, '"Short Description"', $arrFields);
			$modelProduct->part_number = self::getDataValue($data, '"Part Number"', $arrFields);
			$modelProduct->url = self::getDataValue($data, '"URL"', $arrFields);
			$modelProduct->tags = self::getDataValue($data, '"Tags"', $arrFields);
			$modelProduct->phase = self::getDataValue($data, '"Phase"', $arrFields);
			$modelProduct->accounting_item_name = self::getDataValue($data, '"Accounting Item Name"', $arrFields);
			$modelProduct->unit_cost_A = self::getDataValue($data, '"Unit Cost A"', $arrFields, 'decimal');
			$modelProduct->unit_price_A = self::getDataValue($data, '"Unit Price A"', $arrFields, 'decimal');
			$modelProduct->unit_cost_B = self::getDataValue($data, '"Unit Cost B"', $arrFields, 'decimal');
			$modelProduct->unit_price_B = self::getDataValue($data, '"Unit Price B"', $arrFields, 'decimal');
			$modelProduct->unit_cost_C = self::getDataValue($data, '"Unit Cost C"', $arrFields, 'decimal');
			$modelProduct->unit_price_C = self::getDataValue($data, '"Unit Price C"', $arrFields, 'decimal');
			$modelProduct->taxable = self::getDataValue($data, '"Taxable"', $arrFields, 'boolean');
			$modelProduct->btu = self::getDataValue($data, '"BTU"', $arrFields,'int');
			$modelProduct->summarize = self::getDataValue($data, '"Summarize"', $arrFields);
			$modelProduct->sales_tax = self::getDataValue($data, '"Sales Tax"', $arrFields);
			$modelProduct->labor_sales_tax = self::getDataValue($data, '"Labor Sales Tax"', $arrFields);
			$modelProduct->dispersion = self::getDataValue($data, '"Dispersion"', $arrFields);
			$modelProduct->bulk_wire = self::getDataValue($data, '"Bulk Wire"', $arrFields);
			$modelProduct->input_terminals = self::getDataValue($data, '"Input Terminals"', $arrFields);
			$modelProduct->input_signals = self::getDataValue($data, '"Input Signals"', $arrFields);
			$modelProduct->input_labels = self::getDataValue($data, '"Input Labels"', $arrFields);
			$modelProduct->output_terminals = self::getDataValue($data, '"Output Terminals"', $arrFields);
			$modelProduct->output_terminals = self::getDataValue($data, '"Output Terminals"', $arrFields);
			$modelProduct->output_terminals = self::getDataValue($data, '"Output Terminals"', $arrFields);
			
			//BEGIN NOMENCLATOR-------------------------------------------------
			$modelNomenclator = Nomenclator::model()->findByAttributes(array('description'=>'Dtools'));
			if(!isset($modelNomenclator))
			{
				$modelNomenclator = new Nomenclator();
				$modelNomenclator->description = 'Dtools';
				$modelNomenclator->save();
			}
			$modelProduct->Id_nomenclator = $modelNomenclator->Id;
			//END NOMENCLATOR-------------------------------------------------
			
			//BEGIN MEASURE UNIT WEIGHT-------------------------------------------------
			$modelMeasureUnitWeight = MeasurementUnit::model()->findByAttributes(array('short_description'=>'kg'));
			if(!isset($modelMeasureUnitWeight))
			{
				$modelMeasureType = MeasurementType::model()->findByAttributes(array('description'=>'weight'));
				if(!isset($modelMeasureType))
				{
					$modelMeasureType->description = 'weight';
					$modelMeasureType->save();
				}
					
				$modelMeasureUnitWeight = new MeasurementUnit();
				$modelMeasureUnitWeight->Id_measurement_type = $modelMeasureType->Id;
				$modelMeasureUnitWeight->short_description = 'kg';
				$modelMeasureUnitWeight->description = 'kilograms';
				$modelMeasureUnitWeight->save();
					
			}
			$modelProduct->Id_measurement_unit_weight = $modelMeasureUnitWeight->Id;
			//END MEASURE UNIT WEIGHT-------------------------------------------------
			
			//BEGIN MEASURE UNIT LINEAR-------------------------------------------------
			$modelMeasureUnitLinear = MeasurementUnit::model()->findByAttributes(array('short_description'=>'mm'));
			if(!isset($modelMeasureUnitLinear))
			{
				$modelMeasureType = MeasurementType::model()->findByAttributes(array('description'=>'linear'));
				if(!isset($modelMeasureType))
				{
					$modelMeasureType->description = 'linear';
					$modelMeasureType->save();
				}
			
				$modelMeasureUnitLinear = new MeasurementUnit();
				$modelMeasureUnitLinear->Id_measurement_type = $modelMeasureType->Id;
				$modelMeasureUnitLinear->short_description = 'mm';
				$modelMeasureUnitLinear->description = 'Milimeters';
				$modelMeasureUnitLinear->save();
			
			}
			$modelProduct->Id_measurement_unit_linear = $modelMeasureUnitLinear->Id;
			//END MEASURE UNIT LINEAR-------------------------------------------------
			
			//BEGIN VOLTS-------------------------------------------------
			$volts = self::getDataValue($data, '"Volts"', $arrFields);
			$modelVolts = Volts::model()->findByAttributes(array('volts'=>$volts));
			if(!isset($modelVolts))
			{
				$modelVolts = new Volts();
				$modelVolts->volts = $volts;
				$modelVolts->save();
			}
			$modelProduct->Id_volts = $modelVolts->Id;
			//END VOLTS-------------------------------------------------
			
			//BEGIN BRAND-------------------------------------------------
			$manufacturer = self::getDataValue($data, '"Manufacturer"', $arrFields);
			$modelBrand = Brand::model()->findByAttributes(array('description'=>$manufacturer));
			if(!isset($modelBrand))
			{
				$modelBrand = new Brand();
				$modelBrand->description = $manufacturer;
				$modelBrand->save();
			}
			$modelProduct->Id_brand = $modelBrand->Id;
			//END BRAND-------------------------------------------------
			
			//BEGIN CATEGORY-------------------------------------------------
			$category = self::getDataValue($data, '"Category"', $arrFields);
			$modelCategory = Category::model()->findByAttributes(array('description'=>$category));
			if(!isset($modelCategory))
			{
				$modelCategory = new Category();
				$modelCategory->description = $category;
				$modelCategory->save();
			}
			$modelProduct->Id_category = $modelCategory->Id;
			//END CATEGORY-------------------------------------------------
			
			//BEGIN SUB-CATEGORY-------------------------------------------------
			$subCategory = self::getDataValue($data, '"Subcategory"', $arrFields);
			$modelSubCategory = SubCategory::model()->findByAttributes(array('description'=>$subCategory));
			if(!isset($modelSubCategory))
			{
				$modelSubCategory = new SubCategory();
				$modelSubCategory->description = $subCategory;
				$modelSubCategory->save();
			}
			$modelProduct->Id_sub_category = $modelSubCategory->Id;
			//END SUB-CATEGORY-------------------------------------------------
			
			//BEGIN PRODUCT-TYPE-------------------------------------------------
			$modelProductType = ProductType::model()->findByAttributes(array('description'=>'--'));
			if(!isset($modelProductType))
			{
				$modelProductType = new ProductType();
				$modelProductType->description = '--';
				$modelProductType->save();
			}
			$modelProduct->Id_product_type = $modelProductType->Id;
			//END PRODUCT-TYPE-------------------------------------------------
			
			//BEGIN SUPPLIER-------------------------------------------------
			$modelSupplier = Supplier::model()->findByAttributes(array('business_name'=>'--'));
			if(!isset($modelSupplier))
			{
				$modelContact = new Contact();
				$modelContact->description = '--';
				$modelContact->telephone_1 = '--';
				$modelContact->email = uniqid().'@bb.com';
				$modelContact->save();
					
				$modelSupplier = new Supplier();
				$modelSupplier->business_name = '--';
				$modelSupplier->Id_contact = $modelContact->Id;
				$modelSupplier->save();
			}
			$modelProduct->Id_supplier = $modelSupplier->Id;
			//END SUPPLIER-------------------------------------------------
			
			$modelProduct->from_dtools = 1;
			$modelProduct->hide = 0;
			$modelProduct->import_code = $importCode;
			
			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollback();
			return null;
		}
		return $modelProduct; 
	}
}
