<?php
namespace backend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permissions=[];//角色权限required

    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permissions','safe']//可以为空的字段也必须有验证规则,safe表示该字段不需要验证
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'角色名称',
            'description'=>'描述',
            'permissions'=>'权限设置'
        ];
    }

    //得到权限在表单显示
    public static function getPermissionOption(){
        $authManager=\Yii::$app->authManager;
        return ArrayHelper::map($authManager->getPermissions(),'name','description');
    }

    //添加角色方法
    public function addRole(){
        $authManager=\Yii::$app->authManager;
        //判断角色是否存在
        if($authManager->getRole($this->name)){
            $this->addError('name','角色已存在');
        }else{
            $role=$authManager->createRole($this->name);
            $role->description=$this->description;
            if($authManager->add($role)){//保存到数据表
                //关联权限选择保存
                foreach ($this->permissions as $perName){
                    $permission=$authManager->getPermission($perName);
                    if($permission) $authManager->addChild($role,$permission);
                }
                return true;
            };

        }
        return false;
    }
    //回显
    public function dataes(Role $role){
        $this->name=$role->name;
        $this->description=$role->description;
        $permissions=\Yii::$app->authManager->getPermissionsByRole($role->name);
        foreach ($permissions as $permission){
            $this->permissions[]=$permission->name;
        }
    }

    //修改角色功能
    public function updateRole($name){
        $authManager=\Yii::$app->authManager;
        $role=$authManager->getRole($name);
        //给角色赋值
        $role->name=$this->name;
        $role->description=$this->description;
        //如果角色名被修改，检查修改后的名称是否已存在
        if($name !=$this->name && $authManager->getRole($this->name)){
            $this->addError('name','角色已存在');
        }else{
            if($authManager->update($name,$role)){
                //去掉所有与该角色关联的权限
                $authManager->removeChildren($role);
                //关联该角色的权限
                foreach ($this->permissions as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    if($permission) $authManager->addChild($role,$permission);
                }
                return true;
            };
        }
        return false;

    }
}