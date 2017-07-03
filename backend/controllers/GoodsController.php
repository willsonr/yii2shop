<?php

namespace backend\controllers;
use backend\components\SphinxClient;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsIntro;
use yii\web\Request;
use yii\web\UploadedFile;
use yii\data\Pagination;
use xj\uploadify\UploadAction;
use backend\models\GoodsSearchForm;
class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new GoodsSearchForm();
        $query=Goods::find();
        if($keyword = \Yii::$app->request->get('keyword')){
            $cl = new SphinxClient();
            $cl->SetServer ( '127.0.0.1', 9312);
            $cl->SetConnectTimeout ( 10 );
            $cl->SetArrayResult ( true );
            $cl->SetMatchMode ( SPH_MATCH_ALL);
            $cl->SetLimits(0, 1000);
            $res = $cl->Query($keyword, 'goods');//shopstore_search
            //var_dump($res);exit;

            if(!isset($res['matches'])){
//                throw new NotFoundHttpException('没有找到xxx商品');
                $query->where(['id'=>0]);
            }else{

                //获取商品id
                //var_dump($res);exit;
                $ids = ArrayHelper::map($res['matches'],'id','id');
                $query->where(['in','id',$ids]);
            }
        }
//搜索功能

       // $model->search($query);

        $page = new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>'5',
        ]);
        $models = $query->offset($page->offset)->limit($page->limit)->all();

        return $this->render('index',['models'=>$models,'page'=>$page,'model'=>$model]);
    }
//添加商品
    public function actionAdd(){
        $models=new Goods();
        $content=new GoodsIntro();
        $days=new GoodsDayCount();
        $cate=GoodsCategory::find()->asArray()->all();
        $request=new Request();
        if($request->isPost){
            $models->load($request->post());
            $content->load($request->post());
            $days->load($request->post());
            $day=date('Ymd',time());
            $list=GoodsDayCount::findOne(['day'=>$day]);
            if(!$list){
                $days->day=$day;
                $days->count++;
                $days->save();
            }else{
                $list->count++;
                $list->save();
            }
            $models->imgFile = UploadedFile::getInstance($models,'imgFile');
            if($models->validate()&&$content->validate()){
                $day2=date('Ymd',time());
                $models->sn=$day2.str_pad($list->count,5,0,STR_PAD_LEFT);
                $models->create_time=time();
                $fileName = '/images/goods/'.uniqid().'.'.$models->imgFile->extension;
                $models->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                //图片地址赋值
                $models->logo = $fileName;
                $models->save();
                $content->goods_id=$models->id;
                $content->save();
                \Yii::$app->session->setFlash('success','添加成功');
                //返回页面
                return $this->redirect(['goods/index']);
            }
        }
        return $this->render('add',['models'=>$models,'content'=>$content,'cate'=>$cate]);
    }
//修改功能
    public function actionEdit($id){
        $models = Goods::findOne(['id' => $id]);
        $content = GoodsIntro::findOne(['goods_id' => $id]);
        $cate = GoodsCategory::findOne(['id' => $id]);
        $days=new GoodsDayCount();
        $cate=GoodsCategory::find()->asArray()->all();
        $request=new Request();
        if($request->isPost){
            $models->load($request->post());
            $content->load($request->post());
            $days->load($request->post());
            $day=date('Ymd',time());
            $list=GoodsDayCount::findOne(['day'=>$day]);
            if(!$list){
                $days->day=$day;
                $days->count++;
                $days->save();
            }else{
                $list->count++;
                $list->save();
            }
            $models->imgFile = UploadedFile::getInstance($models,'imgFile');
            if($models->validate()&&$content->validate()){
                $day2=date('Ymd',time());
                $models->sn=$day2.str_pad($list->count,5,0,STR_PAD_LEFT);
                $models->create_time=time();
                $fileName = '/images/goods/'.uniqid().'.'.$models->imgFile->extension;
                $models->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                //图片地址赋值
                $models->logo = $fileName;
                $models->save();
                $content->goods_id=$models->id;
                $content->save();
                \Yii::$app->session->setFlash('success','修改成功');
                //返回页面
                return $this->redirect(['goods/index']);
            }
        }
        return $this->render('add',['models'=>$models,'content'=>$content,'cate'=>$cate]);
    }

    public function actionSs()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }


    public function actionDel($id)
    {

        $model=Goods::findOne(['id'=>$id]);

        $model->status=0;

        $model->save(false);
        return $this->redirect(['goods/index']);
    }
//商品相册列表
    public function actionGallery($id)
    {
        $goods = Goods::findOne(['id'=>$id]);
        if($goods == null){
            throw new NotFoundHttpException('商品不存在');
        }


        return $this->render('gallery',['goods'=>$goods]);

    }

//AJAX删除图片
    public function actionDelGallery(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsGallery::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }

    }


    public function actions() {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "",//图片访问路径前缀
                    "imagePathFormat" => "/upload/{yyyy}{mm}{dd}/{time}{rand:6}" ,//上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot"),
                ],
            ],

            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload/logo',
                'baseUrl' => '@web/upload/logo',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                /*'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "/{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //图片上传成功的同时，将图片和商品关联起来
                    $model = new GoodsGallery();
                    $model->goods_id = \Yii::$app->request->post('goods_id');
                    $model->path = $action->getWebUrl();
                    $model->save();
                    $action->output['fileUrl'] = $model->path;
                },
            ],
        ];
    }

}
