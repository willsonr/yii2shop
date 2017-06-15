<?php
namespace backend\models;

use yii\base\Model;

class LoginForm extends Model
{
    public $username;//用户名
    public $password;//密码
    public $code;
    public $rememberMe;
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            //添加自定义验证方法
            ['username', 'validateUsername'],
            ['code','captcha','captchaAction'=>'user/captcha'],
            ['rememberMe','boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'rememberMe'=>'记住我'
        ];
    }


    public function validateUsername(){
        $user=User::findOne(['username'=>$this->username]);
        if($user){
            //用户存在，验证密码
            if(\Yii::$app->security->validatePassword($this->password,$user->password_hash)){
                //账号秘密正确，登录

                    $duration=$this->rememberMe?7*24*3600:0;

                \Yii::$app->user->login($user,$duration);
                return true;
            }else{
                $this->addError('password','密码不正确');
            }
        }else{

            //账号不存在  错误
            $this->addError('username','账号不正确');
        }
        return false;
    }

}