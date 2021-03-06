<?php

/**
 * This is the model class for table "product_requirement_multimedia".
 *
 * The followings are the available columns in table 'product_requirement_multimedia':
 * @property integer $Id_product_requirement
 * @property integer $Id_multimedia
 */
class ProductRequirementMultimedia extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductRequirementMultimedia the static model class
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
		return 'product_requirement_multimedia';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_product_requirement, Id_multimedia', 'required','message'=>'{attribute} '.Yii::app()->lc->t('cannot be blank.')),
			array('Id_product_requirement, Id_multimedia', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id_product_requirement, Id_multimedia', 'safe', 'on'=>'search'),
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
			'productRequirement' => array(self::BELONGS_TO, 'ProductRequirement', 'Id_product_requirement'),
			'multimedia' => array(self::BELONGS_TO, 'Multimedia', 'Id_multimedia'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_product_requirement' => 'Id Product Requirement',
			'Id_multimedia' => 'Id Multimedia',
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

		$criteria->compare('Id_product_requirement',$this->Id_product_requirement);
		$criteria->compare('Id_multimedia',$this->Id_multimedia);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}