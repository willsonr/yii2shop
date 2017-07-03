<?php

namespace backend\controllers;

use backend\models\Menu;

class MenuController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $models=Menu::find()->all();
        return $this->render('index',['models'=>$models]);
    }
    //添加菜单

    public function actionAdd(){
        $model=new Menu();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['menu/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
     //修改菜单
    public function actionEdit($id){
        $model=Menu::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['menu/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除菜单
    public function actionDel($id){
        Menu::findOne($id)->delete();
        return $this->redirect(['menu/index']);
    }
}
