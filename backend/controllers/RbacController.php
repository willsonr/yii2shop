<?php

namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\web\NotFoundHttpException;

class RbacController extends \yii\web\Controller
{
    public function actionIndexPermission()
    {
        $models = \Yii::$app->authManager->getPermissions();
        return $this->render('index-permission', ['models' => $models]);
    }

    //添加权限
    public function actionAddPermission()
    {
        $model = new PermissionForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->addPermission()) {
                \Yii::$app->session->setFlash('success', '添加权限成功');
                return $this->redirect(['index-permission']);
            }
        }

        return $this->render('add-permission', ['model' => $model]);
    }

    //修改权限
    public function actionEditPermission($name)
    {
        //找到权限
        $permission=\Yii::$app->authManager->getPermission($name);
        //判断权限是否存在
        if($permission==null){
            throw new NotFoundHttpException('权限不存在');
        }
        $model = new PermissionForm();
        //回显数据
        $model->loadData($permission);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            if ($model->updatePermission($name)) {
                \Yii::$app->session->setFlash('success', '添加权限成功');
                return $this->redirect(['index-permission']);
            }
        }

        return $this->render('add-permission', ['model' => $model]);
    }
    //删除权限
    public function actionDelPermission($name){
        //找到权限
        $permission=\Yii::$app->authManager->getPermission($name);
        //判断权限是否存在
        if($permission==null){
            throw new NotFoundHttpException('权限不存在');
        }
        \Yii::$app->authManager->remove($permission);
        \Yii::$app->session->setFlash('success', '删除权限成功');
        return $this->redirect(['index-permission']);
    }

    //角色的增删改查
    //添加角色
    public function actionAddRole(){
        $model=new RoleForm();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->addrole()){
                \Yii::$app->session->setFlash('success','添加角色成功');
                return $this->redirect(['index-role']);
            }
        }
        return $this->render('add-role',['model'=>$model]);
    }
    //角色列表
    public function actionIndexRole(){
        $models=\Yii::$app->authManager->getRoles();
        return $this->render('index-role',['models'=>$models]);
    }

    //修改角色
    public function actionEditRole($name){
        $role=\Yii::$app->authManager->getRole($name);
        if($role==null){
            throw new NotFoundHttpException('角色不存在');
        }
        $model=new RoleForm();
        //回显
        $model->dataes($role);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            if ($model->updateRole($name)) {
                \Yii::$app->session->setFlash('success', '添加权限成功');
                return $this->redirect(['index-role']);
            }
        }
        return $this->render('add-role', ['model' => $model]);
    }

    //删除功能
    public function actionDelRole($name){
        $role=\Yii::$app->authManager->getRole($name);
        //判断权限是否存在
        if($role==null){
            throw new NotFoundHttpException('用户不存在');
        }
        \Yii::$app->authManager->remove($role);
        \Yii::$app->session->setFlash('success', '删除用户成功');
        return $this->redirect(['index-role']);
    }


     //用户和角色关联

}
