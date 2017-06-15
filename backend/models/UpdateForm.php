<?php
namespace backend\models;

use yii\base\Model;

class UpdateForm extends Model
{
    public $password_hash;//旧密码
    public $password_hash1;//新密码
    public $password_hash2;

    public function rules()
    {
        return [
            [['password_hash','password_hash1','password_hash2'], 'required'],
            ['password_hash1','string','length'=>[6,32]],
            ['password_hash2', 'compare', 'compareAttribute'=>'password_hash1','message'=>'两次输入密码不一致'],
            ['password_hash', 'validatePassword'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'password_hash' => '旧密码',
            'password_hash1' => '新密码',
            'password_hash2' => '确认新密码'
        ];
    }

    public function validatePassword(){
        $id = \Yii::$app->user->id;
        $user = User::findOne(['id'=>$id]);
    if(!\Yii::$app->security->validatePassword($this->password_hash,$user->password_hash)){
        $this->addError('password_hash','旧密码不正确');
    }

}
}
