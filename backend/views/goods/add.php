<?php
use yii\helpers\ArrayHelper;
use backend\models\Brand;
use yii\widgets\ActiveForm;
$date=\backend\models\Brand::find()->all();
$form=\yii\bootstrap\ActiveForm::begin();//表单开始
echo $form->field($models,'name')->textInput();
if($models->sn){
    echo $form->field($models,'sn')->textInput();
}else{
    echo '';
}
echo $form->field($models,'imgFile')->fileInput()->label('LOGO');
//商品分类开始
echo '<ul id="treeDemo" class="ztree"></ul>';
//echo $form->field($models,'goods_category_id')->textInput();
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$zNodes = \yii\helpers\Json::encode($cate);
$js = new \yii\web\JsExpression(
    <<<JS
var zTreeObj;
    // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: 0
            }
        },
        callback: {
		    onClick: function(event, treeId, treeNode) {
                //将选中节点的id赋值给表单parent_id
               console.debug($("#goods_category_id").val(treeNode.id)) 
            }
	    }
    };
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes = {$zNodes};
    
    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    zTreeObj.expandAll(true);//展开所有节点

JS

);
$this->registerJs($js);

//商品分类结束
echo $form->field($models,'goods_category_id')->hiddenInput(['id'=>'goods_category_id']);

$date=ArrayHelper::map($date,'id','name');
echo $form->field($models,'brand_id')->dropDownList($date,['prompt'=>'请选择分类']);
echo $form->field($models,'market_price')->textInput();
echo $form->field($models,'shop_price')->textInput();
echo $form->field($models,'stock')->textInput();
echo $form->field($models,'is_on_sale')->radioList([1=>'在售',0=>'下架']);
echo $form->field($models,'status')->radioList(\backend\models\Goods::$staOptions);
echo $form->field($models,'sort')->textInput();
echo $form->field($content,'content')->widget('kucha\ueditor\UEditor',[]);

echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();//表单结束