<?php

/**
 * This is the model class for table "review_type".
 *
 * The followings are the available columns in table 'review_type':
 * @property integer $Id
 * @property string $description
 * @property integer $is_internal
 * @property integer $is_for_client
 * @property string $long_description
 * @property integer $has_tag_tracking
 *
 * The followings are the available model relations:
 * @property Review[] $reviews
 * @property Tag[] $tags
 */
class ReviewType extends TapiaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ReviewType the static model class
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
		return 'review_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('description', 'required','message'=>'{attribute} '.Yii::app()->lc->t('cannot be blank.')),
			array('is_internal, is_for_client, has_tag_tracking', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>255),
			array('long_description', 'length', 'max'=>512),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, description, is_internal, is_for_client, has_tag_tracking', 'safe', 'on'=>'search'),
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
			'reviews' => array(self::HAS_MANY, 'Review', 'Id_review_type'),
			'tags' => array(self::MANY_MANY, 'Tag', 'tag_review_type(Id_review_type, Id_tag)'),
			'userGroups' => array(self::MANY_MANY, 'UserGroup', 'review_type_user_group(Id_review_type,Id_user_group)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'description' => 'Nombre',
			'long_description' => 'Descripci&oacute;n',
			'is_internal' => 'Es interno',
			'is_for_client'=> 'Es para cliente',
			'has_tag_tracking'=> 'Con seguimiento',
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

		$criteria->compare('description',$this->description,true);
		$criteria->compare('long_description',$this->long_description,true);
		$criteria->compare('has_tag_tracking',$this->has_tag_tracking);
		
		$criteria->addCondition('t.Id <> 10'); //para que no traiga el formulario para Clientes Iniciales
		$sort=new CSort;
		$sort->defaultOrder ="description ASC";
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
				'sort'=>$sort,
		));
	}
}