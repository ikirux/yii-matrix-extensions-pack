<?php

class User extends CActiveRecord
{
	const STATUS_NOACTIVE = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_BANNED = -1;
	
	//TODO: Delete for next version (backward compatibility)
	const STATUS_BANED = -1;
	
	/**
	 * The followings are the available columns in table 'users':
	 * @var integer $id
	 * @var string $username
	 * @var string $password
	 * @var string $email
	 * @var string $activkey
	 * @var integer $createtime
	 * @var integer $lastvisit
	 * @var integer $superuser
	 * @var integer $status
     * @var timestamp $create_at
     * @var timestamp $lastvisit_at
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return Yii::app()->getModule('user')->tableUsers;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.CConsoleApplication
		return (((get_class(Yii::app()) == 'CConsoleApplication') 
			|| ((get_class(Yii::app()) != 'CConsoleApplication') 
			&& Yii::app()->getModule('user')->isAdmin())) ? [
			['username', 'length', 'max' => 20, 'min' => 3,'message' => UserModule::t("Incorrect username (length between 3 and 20 characters).")],
			['password', 'length', 'max' => 128, 'min' => 4,'message' => UserModule::t("Incorrect password (minimal length 4 symbols).")],
			['email', 'email'],
			['username', 'unique', 'message' => UserModule::t("This user's name already exists.")],
			['email', 'unique', 'message' => UserModule::t("This user's email address already exists.")],
			['username', 'match', 'pattern' => '/^[A-Za-z0-9_\.]+$/u', 'message' => UserModule::t("Incorrect symbols (A-z0-9).")],
			['status', 'in', 'range' => [self::STATUS_NOACTIVE, self::STATUS_ACTIVE, self::STATUS_BANNED]],
			['superuser', 'in', 'range' => [0, 1]],
            ['create_at', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'],
            ['lastvisit_at', 'default', 'value' => null, 'setOnEmpty' => true, 'on' => 'insert'],
			['username, email, superuser, status', 'required'],
			['superuser, status', 'numerical', 'integerOnly' => true],
			['id, username, password, email, activkey, create_at, lastvisit_at, superuser, status', 'safe', 'on' => 'search'],
		] : ((Yii::app()->user->id == $this->id) ? [
			['username, email', 'required'],
			['username', 'length', 'max' => 20, 'min' => 3, 'message' => UserModule::t("Incorrect username (length between 3 and 20 characters).")],
			['email', 'email'],
			['username', 'unique', 'message' => UserModule::t("This user's name already exists.")],
			['username', 'match', 'pattern' => '/^[A-Za-z0-9_\.]+$/u', 'message' => UserModule::t("Incorrect symbols (A-z0-9).")],
			['email', 'unique', 'message' => UserModule::t("This user's email address already exists.")],
		] : []));
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
        $relations = Yii::app()->getModule('user')->relations;

        if (!isset($relations['profile'])) {
            $relations['profile'] = array(self::HAS_ONE, 'Profile', 'user_id');
        }

        return $relations;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => UserModule::t("Id"),
			'username' => UserModule::t("username"),
			'password' => UserModule::t("password"),
			'verifyPassword' => UserModule::t("Retype Password"),
			'email' => UserModule::t("E-mail"),
			'verifyCode' => UserModule::t("Verification Code"),
			'activkey' => UserModule::t("activation key"),
			'createtime' => UserModule::t("Registration date"),
			'create_at' => UserModule::t("Registration date"),
			'lastvisit_at' => UserModule::t("Last visit"),
			'superuser' => UserModule::t("Superuser"),
			'status' => UserModule::t("Status"),
		];
	}
	
	public function scopes()
    {
        return [
            'active' => [
                'condition' => 'status=' . self::STATUS_ACTIVE,
            ],
            'notactive' => [
                'condition' => 'status=' . self::STATUS_NOACTIVE,
            ],
            'banned' => [
                'condition' => 'status=' . self::STATUS_BANNED,
            ],
            'superuser' => [
                'condition' => 'superuser=1',
            ],
            'notsafe' => [
            	'select' => 'id, username, password, email, activkey, create_at, lastvisit_at, superuser, status',
            ],
        ];
    }
	
	public function defaultScope()
    {
        return CMap::mergeArray(Yii::app()->getModule('user')->defaultScope, [
            'alias' => 'user',
            'select' => 'user.id, user.username, user.email, user.create_at, user.lastvisit_at, user.superuser, user.status',
        ]);
    }
	
	public static function itemAlias($type, $code = NULL) {
		$_items = [
			'UserStatus' => [
				self::STATUS_NOACTIVE => UserModule::t('Not active'),
				self::STATUS_ACTIVE => UserModule::t('Active'),
				self::STATUS_BANNED => UserModule::t('Banned'),
			],
			'AdminStatus' => [
				'0' => UserModule::t('No'),
				'1' => UserModule::t('Yes'),
			],
		];

		if (isset($code)) {
			return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
		} else {
			return isset($_items[$type]) ? $_items[$type] : false;
		}
	}
	
	/**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        
        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('activkey', $this->activkey);
        $criteria->compare('create_at', $this->create_at);
        $criteria->compare('lastvisit_at', $this->lastvisit_at);
        $criteria->compare('superuser', $this->superuser);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider(get_class($this), [
            'criteria' => $criteria,
        	'pagination' => [
				'pageSize' => Yii::app()->getModule('user')->user_page_size,
			],
        ]);
    }

    public function getCreatetime() {
        return strtotime($this->create_at);
    }

    public function setCreatetime($value) {
        $this->create_at=date('Y-m-d H:i:s', $value);
    }

    public function getLastvisit() {
        return strtotime($this->lastvisit_at);
    }

    public function setLastvisit($value) {
        $this->lastvisit_at=date('Y-m-d H:i:s', $value);
    }

    public function afterSave() {
        if (get_class(Yii::app()) == 'CWebApplication' && Profile::$regMode == false) {
            Yii::app()->user->updateSession();
        }
        
        return parent::afterSave();
    }
}