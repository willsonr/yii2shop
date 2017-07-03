<?php
namespace frontend\models;


use yii\base\Model;

class MemberForm extends Model{
    public $password=666666;


    public function rules()
    {
        return [
            [['password'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password'=>'新密码'
        ];
    }


}