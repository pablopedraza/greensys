<?php

/**
 * This is the model class for table "budget_item".
 *
 * The followings are the available columns in table 'budget_item':
 * @property integer $Id
 * @property integer $Id_product
 * @property integer $Id_budget
 * @property integer $version_number
 * @property string $price
 * @property integer $Id_budget_item
 * @property integer $Id_price_list
 * @property integer $Id_shipping_type
 * @property integer $Id_area
 * @property integer $quantity
 * @property integer $is_included
 *
 * The followings are the available model relations:
 * @property Budget $versionNumber
 * @property Area $idArea
 * @property Budget $idBudget
 * @property BudgetItem $idBudgetItem
 * @property BudgetItem[] $budgetItems
 * @property Product $idProduct
 * @property PriceList $idPriceList
 * @property ShippingType $idShippingType
 * @property ProductItem[] $productItems
 */
class BudgetItem extends CActiveRecord
{
	
	public $product_code;
	public $product_code_supplier;
	public $product_brand_desc;
	public $product_supplier_name;
	public $product_customer_desc;
	public $area_desc;
	public $parent_product_code;
	public $total_price;
	public $children_total_price;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BudgetItem the static model class
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
		return 'budget_item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_product, Id_budget, version_number, Id_area', 'required'),
			array('Id_product, Id_budget, version_number, Id_budget_item, Id_price_list, Id_shipping_type, Id_area, quantity, is_included', 'numerical', 'integerOnly'=>true),
			array('price', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, Id_product, Id_area, Id_budget, version_number, price, Id_budget_item, Id_price_list, Id_shipping_type,product_code, product_code_supplier, product_brand_desc, product_supplier_name, product_customer_desc, area_desc, parent_product_code, quantity', 'safe', 'on'=>'search'),
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
			'budgetItem' => array(self::BELONGS_TO, 'BudgetItem', 'Id_budget_item'),
			'budgetItems' => array(self::HAS_MANY, 'BudgetItem', 'Id_budget_item'),
			'product' => array(self::BELONGS_TO, 'Product', 'Id_product'),
			'area' => array(self::BELONGS_TO, 'Area', 'Id_area'),
			'priceList' => array(self::BELONGS_TO, 'PriceList', 'Id_price_list'),
			'shippingType' => array(self::BELONGS_TO, 'ShippingType', 'Id_shipping_type'),
			'productItems' => array(self::HAS_MANY, 'ProductItem', 'Id_budget_item'),
			'budget' => array(self::BELONGS_TO, 'Budget', 'Id_budget, version_number'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'Id_product' => 'Product',
			'Id_budget' => 'Budget',
			'version_number' => 'Version',
			'price' => 'Price',
			'Id_budget_item' => 'Budget Item',
			'Id_price_list' => 'Price List',
			'Id_shipping_type' => 'Shipping Type',
			'product_code'=>'Code',
			'product_code_supplier'=>'Code Supplier',
			'product_customer_desc'=>'Description Customer',
			'product_brand_desc'=>'Brand Description',
			'product_supplier_name'=>'Supplier Name',
			'Id_area'=>'Area',
			'parent_product_code'=>'Parent Code',
			'quantity'=>'Quantity',
		);
	}

	public function getChildrenCount()
	{
		$count = BudgetItem::model()->countByAttributes(array('Id_budget_item'=>$this->Id));
		return $count;
	}
	
	public function getChildrenTotalPrice()
	{
		$criteria=new CDbCriteria;
	
		$criteria->select='sum(price * quantity) as children_total_price';
		$criteria->condition='t.Id_budget_item = '.$this->Id;
	
		$modelTotal = BudgetItem::model()->find($criteria);
	
		return $modelTotal->children_total_price;
	}
	
	public function getTotalPrice()
	{	
		return $this->getChildrenTotalPrice() + $this->price;
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

		$criteria->compare('t.Id',$this->Id);
		$criteria->compare('t.Id_product',$this->Id_product);
		$criteria->compare('t.Id_area',$this->Id_area);
		$criteria->compare('t.Id_budget',$this->Id_budget);
		$criteria->compare('t.version_number',$this->version_number);
		$criteria->compare('t.Id_budget_item',$this->Id_budget_item);
		$criteria->compare('t.price',$this->price,true);
		$criteria->compare('t.Id_price_list',$this->Id_price_list);
		$criteria->compare('t.Id_shipping_type',$this->Id_shipping_type);
		$criteria->compare('quantity',$this->quantity);
		
		if(!isset($this->Id_budget_item))
			$criteria->addCondition('isnull(t.Id_budget_item)');
		
		$criteria->join =	"LEFT OUTER JOIN product p ON p.Id=t.Id_product
												 LEFT OUTER JOIN brand b ON p.Id_brand=b.Id
												 LEFT OUTER JOIN area a ON a.Id = t.Id_area
												 LEFT OUTER JOIN supplier s ON p.Id_supplier=s.Id
												 LEFT OUTER JOIN budget_item bi ON bi.Id=t.Id_budget_item
												 LEFT OUTER JOIN product p2 ON p2.Id=bi.Id_product";
		
		$criteria->addSearchCondition("p.code",$this->product_code);
		$criteria->addSearchCondition("p.code_supplier",$this->product_code_supplier);
		$criteria->addSearchCondition("p.description_customer",$this->product_customer_desc);
		$criteria->addSearchCondition("b.description",$this->product_brand_desc);
		$criteria->addSearchCondition("s.business_name",$this->product_supplier_name);
		$criteria->addSearchCondition("a.description",$this->area_desc);
		$criteria->addSearchCondition("p2.code",$this->parent_product_code);
		
		// Create a custom sort
		$sort=new CSort;
		$sort->attributes=array(
											      'product_code' => array(
											        'asc' => 'p.code',
											        'desc' => 'p.code DESC',
		),
													'product_code_supplier' => array(
											        'asc' => 'p.code_supplier',
											        'desc' => 'p.code_supplier DESC',
		),
													'area_desc' => array(
													        'asc' => 'a.description',
													        'desc' => 'a.description DESC',
		),
											      'parent_product_code' => array(
											        'asc' => 'p2.code',
											        'desc' => 'p2.code DESC',
		),
													'product_customer_desc' => array(
													        'asc' => 'p.description_customer',
													        'desc' => 'p.description_customer DESC',
		),
													'product_brand_desc'=> array(
													'asc'=>'b.description',
													'desc'=>'b.description DESC'
		),
													'product_supplier_name'=> array(
													'asc'=>'s.business_name',
													'desc'=>'s.business_name DESC'
		),
							'*',
		);
		
		return new CActiveDataProvider($this, array(
													'criteria'=>$criteria,
													'sort'=>$sort,
		));
	}
	public function searchByProduct()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
	
		$criteria=new CDbCriteria;
	
		$criteria->compare('t.Id',$this->Id);
		$criteria->compare('t.Id_product',$this->Id_product);
		$criteria->compare('t.Id_area',$this->Id_area);
		$criteria->compare('t.Id_budget',$this->Id_budget);
		$criteria->compare('t.version_number',$this->version_number);
		$criteria->compare('t.Id_budget_item',$this->Id_budget_item);
		$criteria->compare('t.price',$this->price,true);
		$criteria->compare('t.Id_price_list',$this->Id_price_list);
		$criteria->compare('t.Id_shipping_type',$this->Id_shipping_type);
		$criteria->compare('quantity',$this->quantity);
	
		$criteria->addCondition('t.version_number in (SELECT MAX(version_number) FROM budget WHERE budget.Id = t.Id_budget)');
	
	
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
}