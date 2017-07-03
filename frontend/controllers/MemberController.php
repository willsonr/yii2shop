<?php

namespace frontend\controllers;

use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Member;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;
class MemberController extends \yii\web\Controller
{
    public $layout;
    public function actionIndex()
    {
        $this->layout = 'login';
        return $this->render('index');
    }
    //用户登录
    public function actionLogin(){
        $model=new LoginForm();
        $this->layout = 'login';
        $request = \Yii::$app->request;
        if( $model->load($request->post())&&$model->validate()){
            $id= \Yii::$app->user->id;
            $user= Member::findOne(['id'=>$id]);
            $user->last_login_time=time();
            $user->last_login_ip=\Yii::$app->request->userIP;
            $user->save(false);
            //跳转到登录页
            \Yii::$app->session->setFlash('success','登录成功');
            //实例化cookie
            $cookies=\Yii::$app->request->cookies;
           //找到用户ID
            $member_id=\Yii::$app->user->id;
            //获得cookie值
            $cookie=$cookies->get('flow');
            //判断cookie
            if($cookie){
                //反序列化
                $cart=unserialize($cookie);
                //遍历
                foreach ($cart as $goods_id=>$value){
                    //获得并给条件用户对应的商品信息
                    $id=Cart::find()->where(['goods_id'=>$goods_id,'member_id'=>$member_id])->one();
                    if($id){
                        //有就累加
                        $id->amount+=$value;
                        $id->save();
                    }else{
                        //没有就添加到数据库中
                        $cart=new Cart();
                        $cart->goods_id=$goods_id;
                        $cart->member_id=$member_id;
                        $cart->amount=$value;
                        $cart->save();
                    }
                }
                //添加数据库后删除浏览器保存的cookie值
                \Yii::$app->response->cookies->remove($cookie);
            }
            return $this->redirect(['index/index']);

        }
        return $this->render('login',['model'=>$model]);
    }
    //注册添加用户
    public function actionRegister(){
        $model=new Member();
        $this->layout = 'login';
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->save(false);
            \Yii::$app->session->setFlash('success','注册成功');
            return $this->redirect(['member/index']);
        }
        return $this->render('register',['model'=>$model]);
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
    //注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
       // \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['member/login']);

    }
//
//    //短信功能
    public function actionSendSms()
    {
        //确保上一次发送短信间隔超过1分钟
        $tel = \Yii::$app->request->post('tel');
        if(!preg_match('/^1[34578]\d{9}$/',$tel)){
            echo '电话号码不正确';
            exit;
        }
        $code = rand(1000,9999);
        //$result = \Yii::$app->sms->setNum($tel)->setParam(['code' => $code])->send();
        $result = 1;
        if($result){
            //保存当前验证码 session  mysql  redis  不能保存到cookie
//            \Yii::$app->session->set('code',$code);
//            \Yii::$app->session->set('tel_'.$tel,$code);
            \Yii::$app->cache->set('tel_'.$tel,$code,5*60);
            echo 'success'.$code;
        }else{
            echo '发送失败';
        }
    }

//
//    public function actionSms()
//    {
//        //安装插件 composer require flc/alidayu
//// 配置信息
//         $config = [
//             'app_key'    => '24480019',
//             'app_secret' => '11df5e3e3d04ad340f5af4cf7c2e5e0d',
//             //'sandbox'    => true,  // 是否为沙箱环境，默认false
//         ];
//
//
// // 使用方法一
//         $client = new Client(new App($config));
//         $req    = new AlibabaAliqinFcSmsNumSend;
//
//         $code = rand(1000,9999);
//
//         $req->setRecNum('13880897691')//设置发给谁（手机号码）
//             ->setSmsParam([
//                 'code' => $code//${code}
//             ])
//             ->setSmsFreeSignName('个人网站验证码')//设置短信签名，必须是已审核的签名
//             ->setSmsTemplateCode('SMS_71475159');//设置短信模板id，必须审核通过
//
//         $resp = $client->execute($req);
//         var_dump($resp);
//         var_dump($code);
//        $code = rand(1000,9999);
//        $result = \Yii::$app->sms->setNum(13880897691)->setParam(['code' => $code])->send();
//        if($result){
//            echo $code.'发送成功';
//        }else{
//            echo '发送失败';
//        }
//    }
}
