<?php
namespace frontend\controllers;


use backend\models\Article;
use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Member;
use frontend\models\MemberForm;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class ApiController extends Controller{
    public $enableCsrfValidation = false;

    public function init()
    {
        \Yii::$app->response->format=Response::FORMAT_JSON;
        parent::init();
    }

    //会员注册
    public function actionMemberRegister(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $member=new Member();
            $member->username=$request->post('username');
            $member->password_hash=$request->post('password');

            $member->email=$request->post('email');
            $member->tel=$request->post('tel');
            $member->code=$request->post('code');
            if($member->validate()){
                $member->save();
                return ['status'=>'1','msg'=>'','data'=>$member->toArray()];
            }
            //验证失败
            return ['status'=>'-1','msg'=>$member->getErrors()];
        }
        return ['status'=>'-1','msg'=>'请使用POST请求'];
    }

    //会员登录
    public function actionLogin(){
        $request=\Yii::$app->request;
        if($request->isPost){
           $user = Member::find()->where(['username'=>$request->post('username')])->one();
            if($user && \Yii::$app->security->validatePassword($request->post('password'),$user->password_hash)){
                \Yii::$app->user->login($user);
                return ['status'=>'1','msg'=>'登录成功'];
            }
            return ['status'=>'-1','msg'=>'账号错误'];
        }
        return ['status'=>'-1','msg'=>'请使用post请求'];
    }


    //获取当前登录用户信息
    public function actionGetCurrentUser()
    {
        if(\Yii::$app->user->isGuest){
            return ['status'=>'-1','msg'=>'请先登录'];
        }
        return ['status'=>'1','msg'=>'','data'=>\Yii::$app->user->identity->toArray()];
    }
  //修改密码
    public function actionMemberEdit(){
        $user_id=\Yii::$app->user->id;
        $member=Member::findOne(['id'=>$user_id]);
        if($member->load($request=\Yii::$app->request->post())&& $member->validate()){
            $member->password_hash=\Yii::$app->security->generatePasswordHash($request->post('password'));

        return ['status'=>'1','msg'=>'修改成功'];
        }
        return ['status'=>'-1','msg'=>'失败'];
    }
   //添加地址
    public function actionAddressAdd(){
        $member_id=\Yii::$app->user->id;
        $model=Address::findOne(['member_id'=>$member_id]);
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->save();
            return ['status'=>'1','msg'=>'添加成功'];
        }
        return ['status'=>'-1','msg'=>$model->getErrors()];
    }
  //修改地址
    public function actionAddressEdit(){
        $address_id=\Yii::$app->request->get('address_id');
        $model=Address::findOne(['id'=>$address_id]);
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->save();
            return ['status'=>'1','msg'=>'修改成功'];
        }
        return ['status'=>'-1','msg'=>$model->getErrors()];
    }
//删除地址
   public function actionAddressDel(){
       $address_id=\Yii::$app->request->get('address_id');
       Address::findOne($address_id)->delete();
       return ['status'=>'1','msg'=>'删除成功'];
   }

   //地址列表
    public function actionAddressList(){
       if(!\Yii::$app->user->isGuest){
            $member_id= \Yii::$app->user->id;
           $model=Address::find()->where(['member_id'=>$member_id])->all();

           return ['status'=>'1','msg'=>'','data'=>$model];
       }
        return ['status'=>'0','msg'=>'请您先登录'];
    }

    //获取所有商品分类
    public function actionGoodscategoryList(){
        $model=GoodsCategory::find()->all();
        return ['status'=>'1','msg'=>'','data'=>$model];
    }
  //获取某分类的所有子分类
    public function actionGoodscategoryZid(){
        $model=GoodsCategory::find()->where(['parent_id'=>0])->all();
        return ['status'=>'1','msg'=>'','data'=>$model];
    }
  //获取某分类的父分类
    public function actionGoodscategoryParent(){

    }
    //某分类下面的所有商品
    public function actionGoodsList(){
        $goods_category_id=\Yii::$app->request->get('goods_category_id');
        $model=Goods::find()->where(['goods_category_id'=>$goods_category_id])->all();
        return ['status'=>'1','msg'=>'','data'=>$model];
    }
    //某品牌下面的所有商品
    public function actionGoodsBrand(){
        $brand_id=\Yii::$app->request->get('brand_id');
        $model=Goods::find()->where(['brand_id'=>$brand_id])->all();
        return ['status'=>'1','msg'=>'','data'=>$model];
    }
    //获取文章分类
    public function actionArticleCategory(){
         $model=Article::find()->all();
        return ['status'=>'1','msg'=>'','data'=>$model];
    }
    //获取某分类下面的所有文章
    public function actionArticleXc(){
        $article_category_id=\Yii::$app->request->get('article_category_id');
        $model=Article::find()->where(['article_category_id'=>$article_category_id])->all();
        return ['status'=>'1','msg'=>'','data'=>$model];
    }
    //获取某文章所属分类
    public function actionArticleCate(){
        
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
    //api/captcha.html?refresh=1//获取新验证码
    //文件上传
    public function actionUpload(){

        $img=UploadedFile::getInstanceByName('img');
        if($img){
            $fileName='/upload/'.uniqid().'.'.$img->extension;
            $result= $img->saveAs(\Yii::getAlias('@webroot').$fileName,0);
            if($result){
                return ['status'=>1,'data'=>$fileName];
            }
            return ['status'=>-1,'msg'=>$img->error];
        }
        return ['status'=>-1,'msg'=>'没有文件上传'];

    }
    //分页
    public function actionList(){
        $per_page=\Yii::$app->request->get('per_page',2);
        $page=\Yii::$app->request->get('page',1);

       $keyword=\Yii::$app->request->get('keyword');

        $page=$page<1?1:$page;
        $query=Goods::find();
        if($keyword){
            $query->andWhere(['like','name',$keyword]);
        }
        $total=$query->count();
        //获取当前页的商品数据
        $goods=$query->offset($per_page*($page-1))->limit($per_page)->asArray()->all();

         return ['status'=>1,'msg'=>'','data'=>[
             'per_page'=>$per_page,
             'page'=>$page,
             'total'=>$total,
             'data'=>$goods
         ]];
    }
   //发送手机验证码
    public function actionSendSms()
    {
        //确保上一次发送短信间隔超过1分钟
        $tel = \Yii::$app->request->post('tel');
        if(!preg_match('/^1[34578]\d{9}$/',$tel)){
            return ['status'=>-1,'msg'=>'电话号码不正确'];

        }
        //检查上次发送时间是否超过1分钟
        $value=\Yii::$app->cache->get('time_tel_'.$tel);
        $s=time()-$value;
        if($s<60){
            return ['status'=>-1,'msg'=>'请'.(60-$s).'秒后再试！'];
        }
        $code = rand(1000,9999);
        //$result = \Yii::$app->sms->setNum($tel)->setParam(['code' => $code])->send();
        $result = 1;
        if($result){
            //保存当前验证码 session  mysql  redis  不能保存到cookie
//            \Yii::$app->session->set('code',$code);
//            \Yii::$app->session->set('tel_'.$tel,$code);
            \Yii::$app->cache->set('tel_'.$tel,$code,5*60);
            \Yii::$app->cache->set('time_tel_'.$tel,time(),5*60);
            return ['status'=>1,'msg'=>''];
        }else{
            return ['status'=>-1,'msg'=>'短信发送失败'];
        }
    }

}