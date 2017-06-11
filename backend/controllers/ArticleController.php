<?php

namespace backend\controllers;

use backend\models\ArticleDetail;
use Yii;
use backend\models\Article;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Request;


class ArticleController extends Controller
{

    public function actionIndex()
    {
        $models = Article::find()->all();

        return $this->render('index', ['models' => $models]);
    }

    public function actionAdd()
    {
        $model = new Article();
        $content= new ArticleDetail();
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            $content->load($request->post());
            if ($model->validate()&&$content->validate()) {
                $model->create_time = time();
                $model->save();
                $content->article_id= \Yii::$app->db->getLastInsertID();
                $content->save();
                \Yii::$app->session->setFlash('success','添加成功');
                //返回页面
                return $this->redirect(['article/index']);
            } else {
                //打印错误信息
                var_dump($model->getErrors());
                exit;
            }
        }

        return $this->render('_form', ['model' => $model,'content'=>$content]);
    }

    public function actionEdit($id)
    {
        $model = Article::findOne(['id' => $id]);
        $content = ArticleDetail::findOne(['article_id' => $id]);
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            $content->load($request->post());
            if ($model->validate()&&$content->validate()) {
                $model->save();
                $content->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article/index']);
            } else {
                //打印错误信息
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('_form', ['model' => $model,'content'=>$content]);
    }


    public function actionDel($id){
        $this->findModel($id)->save();
//        Article::deleteAll(['id'=>$id]);
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            $model->status=-1;
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionContent($id){
        $models=Article::findOne(['id' => $id]);
        $content=ArticleDetail::findOne(['article_id' => $id]);
//        var_dump($content);exit;
        return $this->render('content',['models'=>$models,'content'=>$content]);
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
}
