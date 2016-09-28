<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $username
 * @property string $auth_key
 * @property string $email_confirm_token
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_WAIT = 2;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['username', 'email_confirm_token', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['auth_key'], 'unique'],
            [['email'], 'default', 'value' => 'admin@mail.ru'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
            'username' => 'Имя пользователя',
            'email' => 'Email',
            'status' => 'Статус',
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }
 
    public static function getStatusesArray()
    {
        return [
            self::STATUS_BLOCKED => 'Заблокирован',
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_WAIT => 'Ожидает подтверждения',
        ];
    }


    public function beforeSave($insert)
    {
       if (parent::beforeSave($insert)) {
          if ($this->isNewRecord) {
              $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
	      $this->password_hash = Yii::$app->security->generatePasswordHash($this->password_hash);
          }
          return true;
      }
      return false;
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }


    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }


    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
	Yii::trace($password, 'EAV DEBUG');

	return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }


}
