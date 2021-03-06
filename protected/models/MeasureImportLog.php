<?php

/**
 * This is the model class for table "measure_import_log".
 *
 * The followings are the available columns in table 'measure_import_log':
 * @property integer $Id
 * @property string $original_file_name
 * @property string $file_name
 * @property integer $Id_measurement_unit_linear
 * @property integer $Id_measurement_unit_weight
 * @property string $creation_date
 * @property string $not_found_model
 * @property integer $Id_brand
 *
 * The followings are the available model relations:
 * @property Brand $idBrand
 * @property MeasurementUnit $idMeasurementUnitLinear
 * @property MeasurementUnit $idMeasurementUnitWeight
 */
class MeasureImportLog extends CActiveRecord
{
	public $unit_weight_description;
	public $unit_linear_description;
	public $brand_description;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'measure_import_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_measurement_unit_linear, Id_measurement_unit_weight, Id_brand', 'required'),
			array('Id_measurement_unit_linear, Id_measurement_unit_weight, Id_brand', 'numerical', 'integerOnly'=>true),
			array('original_file_name, file_name', 'length', 'max'=>100),
			array('creation_date, not_found_model', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, original_file_name, file_name, Id_measurement_unit_linear, Id_measurement_unit_weight, creation_date, not_found_model, unit_weight_description,unit_linear_description, Id_brand, brand_description', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'brand' => array(self::BELONGS_TO, 'Brand', 'Id_brand'),
			'measurementUnitLinear' => array(self::BELONGS_TO, 'MeasurementUnit', 'Id_measurement_unit_linear'),
			'measurementUnitWeight' => array(self::BELONGS_TO, 'MeasurementUnit', 'Id_measurement_unit_weight'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'original_file_name' => 'File Name',
			'file_name' => 'File Name',
			'Id_measurement_unit_linear' => 'Unit Linear',
			'Id_measurement_unit_weight' => 'Unit Weight',
			'creation_date' => 'Creation Date',
			'not_found_model' => 'Not Found Model',
			'unit_weight_description'=>'Unit Weight',
			'unit_linear_description'=>'Unit Linear',
			'brand_description' => 'Brand',
			'Id_brand'=>'Brand',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('Id',$this->Id);
		$criteria->compare('original_file_name',$this->original_file_name,true);
		$criteria->compare('file_name',$this->file_name,true);
		$criteria->compare('Id_measurement_unit_linear',$this->Id_measurement_unit_linear);
		$criteria->compare('Id_measurement_unit_weight',$this->Id_measurement_unit_weight);
		$criteria->compare('creation_date',$this->creation_date,true);
		$criteria->compare('not_found_model',$this->not_found_model,true);
		
		
		$criteria->with[]='measurementUnitLinear';
		$criteria->compare('measurementUnitLinear.short_description',$this->unit_linear_description,true);
		
		$criteria->with[]='measurementUnitWeight';
		$criteria->compare('measurementUnitWeight.short_description',$this->unit_weight_description,true);
		
		$criteria->with[]='brand';
		$criteria->compare('brand.description',$this->brand_description,true);
		
		$criteria->order = 't.creation_date DESC';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MeasureImportLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
