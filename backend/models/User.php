<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $update_ip
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */

    static public $staOptions=[-1=>'删除',0=>'崩坏',1=>'正常'];
    public $roles;
    public static function tableName()
    {
        return 'user';
    }
    public static function getRolesOption(){
        $authManager=\Yii::$app->authManager;
        return ArrayHelper::map($authManager->getRoles(),'name','description');
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','password_hash','status'], 'required'],
            [['status', 'created_at', 'updated_at', ], 'integer'],
            [['username', 'password_hash', 'email'], 'string', 'max' => 255],
            //[['auth_key'], 'string', 'max' => 32], 'password_reset_token'
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['email'], 'email'],
            ['update_ip','string'],
            [['roles','email'],'safe']

           // [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password_hash' => '密码',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'status' => '状态',
            'created_at' => '注册时间',
            'updated_at' => '最后登录时间',
            'update_ip' => '最后登录IP',
        ];
    }
//添加用户角色
    public function addRole(){
        $authManager=Yii::$app->authManager;
        $model=new User();
        $id=$model->id;
//        if($authManager->getRole($this->roles)){
//            $this->addError('name','角色已存在');
//        }else{
            $role= $authManager->getRole($this->roles);
            if($authManager->assign($role,$id)){
                foreach ($this->roles as $role){
                    $rol=$authManager->getRole($role);
                    if($rol) $authManager->addChild($role,$rol);
                }
                return true;
            };


         return false;
    }







    public function beforeSave($insert)
    {
        if($insert){
            //生成随机字符串
            $this->auth_key = Yii::$app->security->generateRandomString();
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
