<?php

/**
 * This is the model class for table "purchase_order".
 *
 * The followings are the available columns in table 'purchase_order':
 * @property integer $Id
 * @property integer $Id_supplier
 * @property integer $Id_shipping_parameter
 * @property string $date_creation
 * @property integer $Id_purchase_order_state
 * @property integer $Id_importer
 * @property integer $Id_shipping_type
 *
 * The followings are the available model relations:
 * @property ShippingParameter $idShippingParameter
 * @property PurchaseOrderState $idPurchaseOrderState
 * @property ShippingType $idShippingType
 * @property Supplier $idSupplier
 * @property Importer $idImporter
 * @property PurchaseOrderItem[] $purchaseOrderItems
 */
class PurchaseOrder extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PurchaseOrder the static model class
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
		return 'purchase_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_supplier, Id_shipping_parameter, Id_purchase_order_state, Id_importer, Id_shipping_type', 'required'),
			array('Id_supplier, Id_shipping_parameter, Id_purchase_order_state, Id_importer, Id_shipping_type', 'numerical', 'integerOnly'=>true),
			array('date_creation', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, Id_supplier, Id_shipping_parameter, date_creation, Id_purchase_order_state, Id_importer, Id_shipping_type', 'safe', 'on'=>'search'),
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
			'idShippingParameter' => array(self::BELONGS_TO, 'ShippingParameter', 'Id_shipping_parameter'),
			'idPurchaseOrderState' => array(self::BELONGS_TO, 'PurchaseOrderState', 'Id_purchase_order_state'),
			'idShippingType' => array(self::BELONGS_TO, 'ShippingType', 'Id_shipping_type'),
			'idSupplier' => array(self::BELONGS_TO, 'Supplier', 'Id_supplier'),
			'idImporter' => array(self::BELONGS_TO, 'Importer', 'Id_importer'),
			'purchaseOrderItems' => array(self::HAS_MANY, 'PurchaseOrderItem', 'Id_purchase_order'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'Id_supplier' => 'Id Supplier',
			'Id_shipping_parameter' => 'Id Shipping Parameter',
			'date_creation' => 'Date Creation',
			'Id_purchase_order_state' => 'Id Purchase Order State',
			'Id_importer' => 'Id Importer',
			'Id_shipping_type' => 'Id Shipping Type',
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
		$criteria->compare('Id_supplier',$this->Id_supplier);
		$criteria->compare('Id_shipping_parameter',$this->Id_shipping_parameter);
		$criteria->compare('date_creation',$this->date_creation,true);
		$criteria->compare('Id_purchase_order_state',$this->Id_purchase_order_state);
		$criteria->compare('Id_importer',$this->Id_importer);
		$criteria->compare('Id_shipping_type',$this->Id_shipping_type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}