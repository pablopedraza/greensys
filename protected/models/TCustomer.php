<?php

/**
 * This is the model class for table "customer".
 *
 * The followings are the available columns in table 'customer':
 * @property integer $Id
 * @property string $username
 * @property integer $Id_person
 * @property integer $Id_contact
 * @property integer $Id_customer_type
 * 
 * The followings are the available model relations:
 * @property User $user
 * @property TMultimedia[] $multimedias
 * @property Note[] $notes
 * @property Contact $idContact
 * @property Person $idPerson
 * @property Contact[] $contacts
 * @property Project[] $projects
 */
class TCustomer extends TapiaActiveRecord
{
	public $name;
	public $last_name;
	public $telephone_1;
	public $email;
	public $contact_description;
	
	public $tag_description;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Customer the static model class
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
		return 'customer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id,Id_person, Id_contact, Id_customer_type', 'required','message'=>'{attribute} '.Yii::app()->lc->t('cannot be blank.')),
			array('Id, Id_person, Id_contact, Id_customer_type', 'numerical', 'integerOnly'=>true),		
			array('username', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, Id_person, Id_contact, username, tag_description, contact_description,name,last_name,telephone_1,email', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'username'),
			'multimedias' => array(self::HAS_MANY, 'TMultimedia', 'Id_multimedia'),
			'notes' => array(self::HAS_MANY, 'Note', 'Id_customer'),
			'customerType' => array(self::BELONGS_TO, 'CustomerType', 'Id_customer_type'),		
			//from green schema
			'contact' => array(self::BELONGS_TO, 'Contact', 'Id_contact'),
			'person' => array(self::BELONGS_TO, 'Person', 'Id_person'),
			'contacts' => array(self::MANY_MANY, 'Contact', 'customer_contact(Id_customer, Id_contact)'),
			'projects' => array(self::HAS_MANY, 'Project', 'Id_customer'),
		);
	}

	public function getCustomerDesc()
	{
		return $this->person->last_name .' - '. $this->person->name;
	}
	
	public function getTagDesc()
	{
		$criteria=new CDbCriteria;
		$criteria->select = "ta.description tag_description";
		$criteria->join =	" INNER JOIN review r on (t.Id = r.Id_customer)
										INNER JOIN (select max(Id) Id from review group by Id_customer) r2 on (r2.Id = r.Id)
												INNER JOIN review_type rt on (r.Id_review_type = rt.Id)
												INNER JOIN tag_review_type trt on (trt.Id_review_type = rt.Id)
												INNER JOIN tag_review tr on (tr.Id_tag = trt.Id_tag AND tr.Id_review = r.Id)
												INNER JOIN tag ta on (tr.Id_tag = ta.Id)";
		$criteria->addSearchCondition("t.Id",$this->Id);
		
		
		$model = TCustomer::model()->find($criteria);
		
		return (isset($model))?$model->tag_description:"";
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'name' => 'Nombre',
			'last_name' => 'Apellido',
			'password' => 'Contrase&ntilde;a',
			'username' => 'Usuario',
			'address'=>'Direcci&oacute;n',
			'email'=>'Correo',
			'building_address' => 'Direcci&oacute;n de obra',
			'phone_house' => 'Tel&eacute;fono Casa',
			'phone_mobile' => 'Tel&eacute;fono M&oacute;vil',
			'description'=>'Designaci&oacute;n',
			'tag_description'=>'Etapa',
			'send_mail'=>'Recibe Correo',
			'contact_description' =>'Designaci&oacute;n',
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
		$criteria->compare('Id_person',$this->Id_person);
		$criteria->compare('Id_contact',$this->Id_contact);
	
		$criteria->join =" INNER JOIN green.person gp on (t.Id_person = gp.Id)
						INNER JOIN green.contact gc on (t.Id_contact = gc.Id)";

		$criteria->addSearchCondition("gp.name",$this->name);
		$criteria->addSearchCondition("gp.last_name",$this->last_name);
		$criteria->addSearchCondition("gc.telephone_1",$this->telephone_1);
		$criteria->addSearchCondition("gc.email",$this->email);
		$criteria->addSearchCondition("gc.description",$this->contact_description);
		$criteria->addCondition("t.username IS NOT NULL");
		
		$sort=new CSort;
		$sort->defaultOrder="gc.description ASC";
		$sort->attributes=array(									      
						    'name' => array(
							        'asc' => 'gp.name',
							        'desc' => 'gp.name DESC',
							),							  
							'last_name' => array(
							        'asc' => 'gp.last_name',
							        'desc' => 'gp.last_name DESC',
							),
							'telephone_1' => array(
							        'asc' => 'gc.telephone_1',
							        'desc' => 'gc.telephone_1 DESC',
							),
							'email' => array(
							        'asc' => 'gc.email',
							        'desc' => 'gc.email DESC',
							),
							'contact_description' => array(
							        'asc' => 'gc.description',
							        'desc' => 'gc.description DESC',
							),
							'*',
			);
		
		return new CActiveDataProvider($this, array(
											'criteria'=>$criteria,
											'sort'=>$sort,
		));
	}
	
	public function searchInitCustomer()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
	
		$criteria=new CDbCriteria;
	
		$criteria->compare('Id',$this->Id);
		$criteria->compare('Id_person',$this->Id_person);
		$criteria->compare('Id_contact',$this->Id_contact);
	
		$criteria->join =" INNER JOIN green.person gp on (t.Id_person = gp.Id)
							INNER JOIN green.contact gc on (t.Id_contact = gc.Id)";
	
		$criteria->addSearchCondition("gp.name",$this->name);
		$criteria->addSearchCondition("gp.last_name",$this->last_name);
		$criteria->addSearchCondition("gc.telephone_1",$this->telephone_1);
		$criteria->addSearchCondition("gc.email",$this->email);
		$criteria->addSearchCondition("gc.description",$this->contact_description);
		$criteria->addCondition("t.username IS NULL");
	
		$sort=new CSort;
		$sort->defaultOrder="gc.description ASC";
		$sort->attributes=array(
							    'name' => array(
								        'asc' => 'gp.name',
								        'desc' => 'gp.name DESC',
		),
								'last_name' => array(
								        'asc' => 'gp.last_name',
								        'desc' => 'gp.last_name DESC',
		),
								'telephone_1' => array(
								        'asc' => 'gc.telephone_1',
								        'desc' => 'gc.telephone_1 DESC',
		),
								'email' => array(
								        'asc' => 'gc.email',
								        'desc' => 'gc.email DESC',
		),
								'contact_description' => array(
								        'asc' => 'gc.description',
								        'desc' => 'gc.description DESC',
		),
								'*',
		);
	
		return new CActiveDataProvider($this, array(
												'criteria'=>$criteria,
												'sort'=>$sort,
		));
	}
	
	public function searchInternal()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
	
		$criteria=new CDbCriteria;
	
		$criteria->compare('Id',$this->Id);
		$criteria->addSearchCondition("gp.name",$this->name);
		$criteria->addSearchCondition("gp.last_name",$this->last_name);
		$criteria->addSearchCondition("gc.telephone_1",$this->telephone_1);
		$criteria->addSearchCondition("gc.email",$this->email);
		$criteria->addSearchCondition("gc.description",$this->contact_description);
		$criteria->addCondition("t.username IS NOT NULL");
				
		$criteria->compare('t.username',$this->username,true);
		$criteria->compare('building_address',$this->building_address,true);
	
		$criteria->join =	" LEFT JOIN review r on (t.Id = r.Id_customer)
										LEFT JOIN green.person gp on (t.Id_person = gp.Id)
										LEFT JOIN green.contact gc on (t.Id_contact = gc.Id)
										LEFT JOIN (select max(Id) Id from review group by Id_customer) r2 on (r2.Id = r.Id)
										LEFT JOIN review_type rt on (r.Id_review_type = rt.Id)
										LEFT JOIN tag_review_type trt on (trt.Id_review_type = rt.Id)
										LEFT JOIN tag_review tr on (tr.Id_tag = trt.Id_tag AND tr.Id_review = r.Id)
										LEFT JOIN tag ta on (tr.Id_tag = ta.Id)";
		$criteria->addSearchCondition("ta.description",$this->tag_description);		
		$criteria->distinct = true;
		// Create a custom sort
		$sort=new CSort;
		$sort->defaultOrder="gc.description ASC";
		$sort->attributes=array(
							      'name',
							      'last_name',
							      'username' => array(
							        'asc' => 't.username',
							        'desc' => 't.username DESC',
								),
							      'building_address',
							      'tag_description' => array(
							        'asc' => 'ta.description',
							        'desc' => 'ta.description DESC',
		),
			'*',
		);
	
		return new CActiveDataProvider($this, array(
									'criteria'=>$criteria,
									'sort'=>$sort,
		));
	}
	
	public function searchNotInternal()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
	
		$criteria=new CDbCriteria;
	
		$criteria->compare('Id',$this->Id);

		$criteria->compare('username',$this->username,true);
		$criteria->compare('building_address',$this->building_address,true);
		
		$criteria->addSearchCondition("gp.name",$this->name);
		$criteria->addSearchCondition("gp.last_name",$this->last_name);
		$criteria->addSearchCondition("gc.telephone_1",$this->telephone_1);
		$criteria->addSearchCondition("gc.email",$this->email);
		$criteria->addSearchCondition("gc.description",$this->contact_description);
		$criteria->addCondition("t.username IS NOT NULL");
				
		
		$criteria->addCondition('t.Id IN(select Id_customer from user_customer where username = "'. User::getCurrentUser()->username.'")');
		
		$criteria->join =	" LEFT JOIN review r on (t.Id = r.Id_customer)
										LEFT JOIN green.person gp on (t.Id_person = gp.Id)
										LEFT JOIN green.contact gc on (t.Id_contact = gc.Id)
				
										LEFT JOIN (select max(Id) Id from review group by Id_customer) r2 on (r2.Id = r.Id)
												LEFT JOIN review_type rt on (r.Id_review_type = rt.Id)
												LEFT JOIN tag_review_type trt on (trt.Id_review_type = rt.Id)
												LEFT JOIN tag_review tr on (tr.Id_tag = trt.Id_tag AND tr.Id_review = r.Id)
												LEFT JOIN tag ta on (tr.Id_tag = ta.Id)";
		$criteria->addSearchCondition("ta.description",$this->tag_description);
		
		$criteria->distinct = true;
		
		// Create a custom sort
		$sort=new CSort;
		$sort->defaultOrder="gc.description ASC";
		$sort->attributes=array(
									      'name',
									      'last_name',
									      'username' => array(
									        'asc' => 't.username',
									        'desc' => 't.username DESC',
		),
									      'building_address',
									      'tag_description' => array(
									        'asc' => 'ta.description',
									        'desc' => 'ta.description DESC',
		),
					'*',
		);
		
		return new CActiveDataProvider($this, array(
											'criteria'=>$criteria,
											'sort'=>$sort,
		));
		
	}
}