<?php

namespace backend\controllers;

use backend\models\Order;
use yii\data\Pagination;

class OrderController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=Order::find();
        $page = new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>'5',
        ]);
        $models = $query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }
  public function actionEdit($id){
        $model=Order::findOne(['id'=>$id]);
        $model->status=0;
        $model->save();
        return $this->redirect(['order/index']);

  }

}
