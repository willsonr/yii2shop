<?php

namespace frontend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $email
 * @property string $tel
 * @property integer $last_login_time
 * @property integer $last_login_ip
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Member extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public $password;
    public $smsCode;
    public $code;
    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

           // ['username','unique','message'=>'该用户已被注册'],
           // [['username'],'match','pattern'=>'^[\u4e00-\u9fa5]{1,7}$|^[\dA-Za-z_]{1,14}$','message'=>'3-20位字符，可由中文、字母、数字和下划线组成'],
           // ['password_hash','' ],
            //['email','unique','message'=>'该邮箱已被注册'],
            [['username','password','password_hash','email','tel'],'required'],
           // [['username'], 'string', 'max' => 20,'min'=>'3','message'=>'3-20位字符，可由中文、字母、数字和下划线组成'],
            [['password'], 'match', 'pattern'=>'/^[a-zA-Z0-9_]{6,20}$/','message'=>'6-20位字符，可使用字母、数字和符号的组合，不建议使用纯数字、纯字母、纯符号'],
            [['password_hash'],'compare','compareAttribute'=>'password','message'=>'重复密码必须一致'],
            [['email'], 'email', 'message'=>'邮箱格式不正确'],
            ['code','captcha','captchaAction'=>'member/captcha'],
            [['tel'], 'match','pattern'=>'/^1[34578]\d{9}$/','message'=>'电话号码格式不正确'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名：',
            'auth_key' => 'Auth Key',
            'password_hash' => '确认密码',
            'email' => '邮箱：',
            'tel' => '电话：',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录ip',
            'status' => '状态',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
            'password'=>'密码：',
           // 'password2'=>'确认密码：',
            'code'=>'验证码：',
            'smsCode'=>'短信验证：'
        ];
    }


    public function beforeSave($insert)
    {
        if($insert){
            $this->password_hash=Yii::$app->security->generatePasswordHash($this->password_hash);
            $this->auth_key=Yii::$app->security->generateRandomString();
            $this->created_at=time();
            $this->last_login_time=time();
            $this->last_login_ip=Yii::$app->request->userIP;
            $this->status=1;
        }else{
            $this->updated_at=time();
        }
        return parent::beforeSave($insert);
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey()==$authKey;
    }
}
