<?php

class User extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'tbl_user':
	 * @var integer $id
	 * @var string $username
	 * @var string $password
	 */
         
    
         public $password_repeat;
         
         
	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
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
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, password_repeat', 'required'),
			array('username, password, password_repeat', 'length', 'max'=>128),
                        array('username','email'),
                        array('username','unique'),
                        array('password_repeat', 'compare', 'compareAttribute'=>'password'),
                        // The following rule is used by search().
                        array('id, username', 'safe', 'on'=>'search'),
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
			'pics' => array(self::HAS_MANY, 'Pic', 'author_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'username' => 'Email',
			'password' => 'Password',
			'password_repeat' => 'Repeat password',
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

            $criteria->compare('id',$this->id);
            $criteria->compare('username',$this->username,true);

            return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
            ));
        }
              
	/**
	 * Checks if the given password is correct.
	 * @param string the password to be validated
	 * @return boolean whether the password is valid
	 */
	public function validatePassword($password)
	{
		return CPasswordHelper::verifyPassword($password,$this->password);
	}

	/**
	 * Generates the password hash.
	 * @param string password
	 * @return string hash
	 */
	public function hashPassword($password)
	{
		return CPasswordHelper::hashPassword($password);
	}
        
        
        protected function beforeSave()
        {
             if(parent::beforeSave())
             {
                if($this->isNewRecord)
                {
                    $this->create_time = time();
                    $this->password = $this->hashPassword($this->password);
                }

                return true;
             }

            return false;
        }
}
