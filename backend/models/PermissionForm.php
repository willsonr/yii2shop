<?php
namespace backend\models;


use yii\base\Model;
use yii\rbac\Permission;

class PermissionForm extends Model{
    public $name;//权限名
    public $description;//权限介绍


    public function rules()
    {
        return [
            [['name','description'],'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'权限名称',
            'description'=>'权限介绍'
        ];
    }

  //实现添加权限功能
    public function addPermission(){
        $authManager=\Yii::$app->authManager;
        //判断权限是否存在
        if($authManager->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }else{
            //赋予权限
            $permission=$authManager->createPermission($this->name);
            //赋值
            $permission->description=$this->description;
            //保存到数据表
            return $authManager->add($permission);
        }
        return false;

    }
    //回显数据
    public function loadData(Permission $permission){
        $this->name=$permission->name;
        $this->description=$permission->description;

    }

    //实现修改权限功能
    public function updatePermission($name){
        $authManager=\Yii::$app->authManager;
        //获取修改权限对象
        $permission=$authManager->getPermission($name);
        //判断权限是否存在
        if($name != $this->name && $authManager->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }else{
            //赋予权限
            $permission->name=$this->name;
            //赋值
            $permission->description=$this->description;
            //保存到数据表
            return $authManager->update($name,$permission);
        }
        return false;

    }


}