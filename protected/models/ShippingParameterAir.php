<?php

/**
 * This is the model class for table "shipping_parameter_air".
 *
 * The followings are the available columns in table 'shipping_parameter_air':
 * @property integer $Id
 * @property double $cost_measurement_unit
 * @property integer $Id_measurement_unit_cost
 * @property double $weight_max
 * @property double $lenght_max
 * @property double $width_max
 * @property double $height_max
 * @property double $volume_max
 * @property integer $Id_measurement_unit_sizes_max
 * @property integer $days
 *
 * The followings are the available model relations:
 * @property ShippingParameter[] $shippingParameters
 * @property MeasurementUnit $idMeasurementUnitCost
 * @property MeasurementUnit $idMeasurementUnitSizesMax
 */
class ShippingParameterAir extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ShippingParameterAir the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'shipping_parameter_air';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id, Id_measurement_unit_cost, Id_measurement_unit_sizes_max', 'required'),
			array('Id, Id_measurement_unit_cost, Id_measurement_unit_sizes_max, days', 'numerical', 'integerOnly'=>true),
			array('cost_measurement_unit, weight_max, lenght_max, width_max, height_max, volume_max', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, cost_measurement_unit, Id_measurement_unit_cost, weight_max, lenght_max, width_max, height_max, volume_max, Id_measurement_unit_sizes_max, days', 'safe', 'on'=>'search'),
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
			'shippingParameters' => array(self::HAS_MANY, 'ShippingParameter', 'Id_shipping_parameter_air'),
			'idMeasurementUnitCost' => array(self::BELONGS_TO, 'MeasurementUnit', 'Id_measurement_unit_cost'),
			'idMeasurementUnitSizesMax' => array(self::BELONGS_TO, 'MeasurementUnit', 'Id_measurement_unit_sizes_max'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'cost_measurement_unit' => 'Cost Measurement Unit',
			'Id_measurement_unit_cost' => 'Id Measurement Unit Cost',
			'weight_max' => 'Weight Max',
			'lenght_max' => 'Lenght Max',
			'width_max' => 'Width Max',
			'height_max' => 'Height Max',
			'volume_max' => 'Volume Max',
			'Id_measurement_unit_sizes_max' => 'Id Measurement Unit Sizes Max',
			'days' => 'Days',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('Id',$this->Id);
		$criteria->compare('cost_measurement_unit',$this->cost_measurement_unit);
		$criteria->compare('Id_measurement_unit_cost',$this->Id_measurement_unit_cost);
		$criteria->compare('weight_max',$this->weight_max);
		$criteria->compare('lenght_max',$this->lenght_max);
		$criteria->compare('width_max',$this->width_max);
		$criteria->compare('height_max',$this->height_max);
		$criteria->compare('volume_max',$this->volume_max);
		$criteria->compare('Id_measurement_unit_sizes_max',$this->Id_measurement_unit_sizes_max);
		$criteria->compare('days',$this->days);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}