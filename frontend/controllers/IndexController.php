<?php


namespace frontend\controllers;
use backend\components\SphinxClient;
use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Locations;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\data\Pagination;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;


class IndexController extends Controller
{
    public $enableCsrfValidation = false;
    public $layout = 'index';
    public function actionIndex(){
        if(\Yii::$app->session->hasFlash('danger')){
            echo "<script>alert('".\Yii::$app->session->getFlash('danger').")</script>";
        }
        $goods =GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->render('index',['goods'=>$goods]);
    }

    public function actionGoods(){
        $this->layout='goods';
        $goods =GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->render('goods',['goods'=>$goods]);
    }
   public function actionCate(){
       $this->layout='cate';
//       var_dump(\Yii::$app->request->get());exit;
       $query=GoodsCategory::find();
//       if($keyword = \Yii::$app->request->get('keyword')){
//           $cl = new SphinxClient();
//           $cl->SetServer ( '127.0.0.1', 9312);
//           $cl->SetConnectTimeout ( 10 );
//           $cl->SetArrayResult ( true );
//           $cl->SetMatchMode ( SPH_MATCH_ALL);
//           $cl->SetLimits(0, 1000);
//           $res = $cl->Query($keyword, 'goods');//shopstore_search
//           //var_dump($res);exit;
//
//           if(!isset($res['matches'])){
////                throw new NotFoundHttpException('没有找到xxx商品');
//               $query->where(['id'=>0]);
//           }else{
//
//               //获取商品id
//               //var_dump($res);exit;
//               $ids = ArrayHelper::map($res['matches'],'id','id');
//               $query->where(['in','id',$ids]);
//           }
//       }

       $goods =$query->where(['parent_id'=>0])->all();
       return $this->render('cate',['goods'=>$goods]);
   }

    //添加到购物车
    public function actionAdd()
    {   $this->layout='flow';
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id'=>$goods_id]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }
        if(\Yii::$app->user->isGuest){
            //未登录
            //先获取cookie中的购物车数据
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('flow');
            if($cookie == null){
                //cookie中没有购物车数据
                $cart = [];
            }else{
                $cart = unserialize($cookie->value);
            }
            //将商品id和数量存到cookie
            $cookies = \Yii::$app->response->cookies;
            //检查购物车中是否有该商品,有，数量累加
            if(key_exists($goods->id,$cart)){
                $cart[$goods_id] += $amount;
            }else{
                $cart[$goods_id] = $amount;
            }
            $cookie = new Cookie([
                'name'=>'flow','value'=>serialize($cart)
            ]);
            $cookies->add($cookie);



        }else{
            //已登录 操作数据库
            //如果登录  cookie里面有数据
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('flow');
            if($cookie){
                //有cookie
                $cart = unserialize($cookie->value);
                //查找有没有该商品,如果有,数量取出来,加上传过来的数量
                if(key_exists($goods_id,$cart)){
                    $amout = $cart[$goods_id]+$amount;
                }
                //如果没有就用post传过来的数据,保存到数据库

                //检查数据库有没有该数据
            }
            //没有cookie 直接存数据库
            //如果数据库有该用户数据查找出来,数量更新
            $member_id = \Yii::$app->user->id;
            $user = Cart::find()->where(['member_id'=>$member_id,'goods_id'=>$goods_id])->one();
            $model = new Cart();
            //查找当前用户数据
            if($user){
                $user->amount += $amount;
                $user->save();
            }else{
                $model->member_id = $member_id;
                $model->goods_id = $goods_id;
                $model->amount = $amount;
                $model->save();
            }
        }
        return $this->redirect(['index/flow']);
    }


   //购物车数据实现
    public function actionFlow(){
        $this->layout='flow';
        if(\Yii::$app->user->isGuest) {
            //取出cookie中的商品id和数量
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('flow');
            if ($cookie == null) {
                //cookie中没有购物车数据
                $cart = [];
            } else {
                $cart = unserialize($cookie->value);
            }
            $models = [];
            foreach ($cart as $good_id => $amount) {
                $goods = Goods::findOne(['id' => $good_id])->attributes;
                $goods['amount'] = $amount;
                $models[] = $goods;
            }
        }else {
            //从数据库获取购物车数据
            $models = null;
            $member_id = \Yii::$app->user->id;
            $carts = Cart::find()->where(['member_id' => $member_id])->all();
            foreach ($carts as $cart) {
                $goods = Goods::findOne(['id' => $cart->goods_id])->attributes;
                $goods['amount'] = $cart->amount;
                $models[] = $goods;
            }
        }
        return $this->render('flow',['models'=>$models]);
    }

    public function actionUpdateCart()
    {
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id'=>$goods_id]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }
        if(\Yii::$app->user->isGuest){
            //未登录
            //先获取cookie中的购物车数据
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('flow');
            if($cookie == null){
                //cookie中没有购物车数据
                $cart = [];
            }else{
                $cart = unserialize($cookie->value);
            }
            //将商品id和数量存到cookie
            $cookies = \Yii::$app->response->cookies;

            if($amount){
                $cart[$goods_id] = $amount;
            }else{
                if(key_exists($goods['id'],$cart)) unset($cart[$goods_id]);
            }
            $cookie = new Cookie([
                'name'=>'flow','value'=>serialize($cart)
            ]);
            $cookies->add($cookie);
        }else{
            //已登录  修改数据库里面的购物车数据
            //用户登录 保存到数据表
            $user = \Yii::$app->user->id;
            if($amount){
                $cart = Cart::find()->where(['goods_id'=>$goods_id,'member_id'=>$user])->one();
                $cart->amount = $amount;
                $cart->save();
            }else{
                Cart::deleteAll(['goods_id'=>$goods_id]);
            }
        }

    }

    //订单生成
    public function actionFlow2(){
        $this->layout='flow2';
        $model = new Order();

        $member_id = \Yii::$app->user->id;
        $goods = $goods_list = [];
        //用户没有联系地址跳转至联系地址填写页面
        if(!Address::findOne(['member_id'=>$member_id])){
            return $this->redirect(['address/index']);
        }
        $carts = Cart::find()->where(['member_id'=>$member_id])->asArray()->all();
        foreach ($carts as $cart){
            $goods_one = Goods::findOne(['id'=>$cart['goods_id']])->attributes;
            if($goods_one){
                $goods_one['amount']=$cart['amount'];
                $goods[] = $goods_one;
            }
        }
        if($model->load(\Yii::$app->request->post())){
            var_dump($model);exit;
        }
        return $this->render('flow2',['model'=>$model,'goods'=>$goods]);
    }
    //执行添加到数据库
    public function actionSave(){
        $this->layout='flow3';
        $model = new Order();
        $address = \Yii::$app->request->post('address');
        $delivery_id = \Yii::$app->request->post('delivery_id');
        $payment_id = \Yii::$app->request->post('payment_id');
        $total = \Yii::$app->request->post('total');

        $data = ['address'=>$address,'delivery_id'=>$delivery_id,'payment_id'=>$payment_id,'total'=>$total];
        $model -> loadData($data);

        if($model->validate()){
            //回滚--事务--innnodb存储引擎

            //开启事务
            $transaction = \Yii::$app->db->beginTransaction();
            try{
                $model->save();
                $carts=Cart::findAll(['member_id'=>\Yii::$app->user->id]);
                foreach ($carts as $cart){
                    $goods=Goods::findOne(['id'=>$cart->goods_id]);
                    if($goods==null){
                        //商品不存在
                      throw new Exception('商品已售完');
                    }
                    if($goods->stock < $cart->amount){
                        //库存不足
                        throw new Exception('商品库存不足！');
                    }
                }
                $order_goods=new OrderGoods();
                //保存数据到order_goods表
                $member_id=Cart::findOne(['member_id'=>\Yii::$app->user->id]);
                $order_goods->goods_id=$member_id->goods_id;
                $order_goods->amount=$member_id->amount;
                $order=Order::findOne(['member_id'=>\Yii::$app->user->id]);
                $order_goods->order_id=$order->id;
                $goods=Goods::findOne(['id'=>$member_id->goods_id]);
                $order_goods->goods_name=$goods->name;
                $order_goods->logo=$goods->logo;
                $order_goods->price=$goods->shop_price;
                $order_goods->total=$member_id->amount*$goods->shop_price;
                Cart::deleteAll(['member_id'=>\Yii::$app->user->id]);
                $order_goods->save();
                //扣库存 //扣减该商品库存
                $goods->stock-=$member_id->amount;
                $goods->save();
                //提交执行
                $transaction->commit();
                return $this->render('flow3');
            }catch (Exception $e){
                //回滚
                echo $e->getMessage();
                $transaction->rollBack();
            }

        }
        return $this->render('flow2');

    }

}
