<?php

namespace backend\controllers;

use backend\models\UpdateForm;
use backend\models\User;
use backend\models\LoginForm;
use yii\web\Request;

class UserController extends \yii\web\Controller
{
    public function actionIndex()
    {    $models=User::find()->all();

        return $this->render('index',['models'=>$models]);
    }
//添加用户
    public function actionAdd(){
         $model=new User();
         $request=new Request();
         if($request->isPost){
             $model->load($request->post());
             $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
             $model->created_at=time();
             $model->updated_at=time();
             $model->update_ip=\Yii::$app->request->userIP;
             if($model->validate()){
                 $model->save();
                 \Yii::$app->session->setFlash('success','添加成功');
                 return $this->redirect(['user/index']);
             }else{
                 var_dump($model->getErrors());exit;
             }
         }

         return $this->render('add',['model'=>$model]);
    }


    //修改功能
    public function actionEdit($id){
        $model=User::findOne(['id'=>$id]);
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
            $model->updated_at=time();
            $model->update_ip=\Yii::$app->request->userIP;
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['user/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }

        return $this->render('add',['model'=>$model]);
    }

    //删除功能
    public function actionDel($id){
        $model=User::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save();
        return $this->redirect(['user/index']);
    }

    //修改密码
    public function actionUpdate()
    {
        $id = \Yii::$app->user->id;
        $old = User::findOne(['id'=>$id]);
        $model = new UpdateForm();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $old->password_hash = $model->password_hash1;
                $old->password_hash = \Yii::$app->security->generatePasswordHash($old->password_hash);

                $old->save(false);
                    \Yii::$app->session->setFlash('success', '修改密码成功');
                    //跳转
                    return $this->redirect(['user/index']);

            }

        }
        return $this->render('update', ['model' => $model]);
    }

    //登录功能
    public function actionLogin(){
        $model=new LoginForm();
        $request = \Yii::$app->request;
        if( $model->load($request->post())&&$model->validate()){
            $id= \Yii::$app->user->id;
            $user= User::findOne(['id'=>$id]);
            $user->auth_key = \Yii::$app->security->generateRandomString();
            $user->updated_at=time();
            $user->update_ip=\Yii::$app->request->userIP;
          // var_dump($user->auth_key);exit;
               $user->save(false);
                //跳转到登录页
            \Yii::$app->session->setFlash('success','登录成功');
                return $this->redirect(['user/index']);

        }
        return $this->render('login',['model'=>$model]);
    }

    //退出 注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['user/login']);

    }

    //验证码
    public function actions(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>4,
            ],
        ];
    }
}
