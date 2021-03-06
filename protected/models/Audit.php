<?php

/**
 * This is the model class for table "audit".
 *
 * The followings are the available columns in table 'audit':
 * @property integer $Id
 * @property string $table_name
 * @property string $operation
 * @property string $date
 * @property string $username
 * @property integer $Id_table
 */
class Audit extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Audit the static model class
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
		return 'audit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_table', 'numerical', 'integerOnly'=>true),
			array('table_name, username', 'length', 'max'=>128),
			array('operation', 'length', 'max'=>45),
			array('date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, table_name, operation, date, username, Id_table', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'table_name' => 'Table Name',
			'operation' => 'Operation',
			'date' => 'Date',
			'username' => 'Username',
			'Id_table' => 'Id Table',
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
		$criteria->compare('table_name',$this->table_name,true);
		$criteria->compare('operation',$this->operation,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('Id_table',$this->Id_table);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}