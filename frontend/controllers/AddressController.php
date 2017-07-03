<?php

namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\Locations;

class AddressController extends \yii\web\Controller
{
    public $layout;
    // 添加地址
    public function actionIndex()
    {
        $model=new Address();
        $this->layout = 'address';
        $locations = new Locations();
       if($model->load(\Yii::$app->request->post())&&$model->validate()){
           $model->makeDefault();
           $model->save();
           return $this->redirect(['address/index']);
       }
        return $this->render('index',['model'=>$model,'locations'=>$locations]);
    }
    //修改
    public function actionEdit($id){
        $model=Address::findOne(['id'=>$id]);
        $cate=new Locations();
        $this->layout = 'address';
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->makeDefault();
            $model->save();
            \Yii::$app->session->setFlash('success','修改地址成功');
            return $this->redirect(['address/index']);
        }
        return $this->render('index',['model'=>$model,'cate'=>$cate]);
    }
    //删除
    public function actionDel($id)
    {
        Address::findOne($id)->delete();
        return $this->redirect(['address/index']);
    }






    //获得省份
   public function actionProvince(){
        $models=Locations::find()->where(['parent_id'=>0])->asArray()->all();
        echo json_encode($models);
   }
   //获取市级
    public function actionArea(){
        $id=\Yii::$app->request->post();
        $model=Locations::find()->where(['parent_id'=>$id])->asArray()->all();
        echo json_encode($model);
    }
}
