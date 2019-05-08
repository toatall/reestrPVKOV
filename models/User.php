<?php

namespace app\models;

/**
 * @todo add AD information user
 */
use app\helpers\AD;


class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;
    
    public static $user = null;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
    	return [
    		[['username'], 'required'],
    		[['username'], 'string'],
    	];
    }
    
	
    /**
     * Создание нового пользователя (который выполнил вход)
     * @todo Добавить информацию из AD
     * @return \app\models\User
     */
    private static function newUser()
    {
    	
    	$username = 'Гость';
    	
    	if (isset($_SERVER['REMOTE_USER']))
    	{
    		$ex = explode('\\',$_SERVER['REMOTE_USER']);
    		if (count($ex) > 0)
    			$username = $ex[1];
    	}
    	    	
    	$id = uniqid();
    	
    	self::$user = new static([
    		'id'=>$id,
    		'username' => $username,
    		'password' => null,
    		'authKey' => 'keyUser' . $username,
    		'accessToken' => $id . '-token',
    	]);
    	
    	return self::$user;
    }
    
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {    
    	if (self::$user === null)
    		self::newUser();
    	
    	return self::$user;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {       
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
    	if (self::$user === null)
    		self::newUser();
    	
    	return self::$user;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
    
    
   
    
}
