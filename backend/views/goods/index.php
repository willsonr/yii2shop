



<?php
if(Yii::$app->user->can('goods/add')) {
echo \yii\bootstrap\Html::a('添加',['goods/add'],['class'=>'btn btn-info btn-xs']);}?>

<?php
$form = \yii\bootstrap\ActiveForm::begin([
    'method' => 'get',
    //get方式提交,需要显式指定action
    'action'=>\yii\helpers\Url::to(['goods/index']),
    'options'=>['class'=>'form-inline']
]);
echo $form->field($model,'name')->textInput(['placeholder'=>'商品名','name'=>'keyword'])->label(false);
echo $form->field($model,'sn')->textInput(['placeholder'=>'货号'])->label(false);
echo $form->field($model,'minPrice')->textInput(['placeholder'=>'￥'])->label(false);
echo $form->field($model,'maxPrice')->textInput(['placeholder'=>'￥'])->label('-');
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-info btn-xs']);
\yii\bootstrap\ActiveForm::end();

?>




<table class="list table table-hover table-bordered">
    <tr class="danger">
        <th>ID</th>
        <th>商品名称</th>
        <th>货号</th>
        <th>LOGO图片</th>
        <th>商品分类</th>
        <th>品牌分类</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>排序</th>
        <th>添加时间</th>
        <th>操作</th>

    </tr>
    <?php foreach ($models as $model): ?>
        <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->sn?></td>
        <td>
            <?=\yii\bootstrap\Html::img($model->logo,['width'=>40])?>
        </td>
        <td><?=$model->goodsCategory->name?></td>
        <td><?=$model->brand->name?></td>
        <td><?=$model->market_price?></td>
        <td><?=$model->shop_price?></td>
        <td><?=$model->stock?></td>
        <td><?=$model->is_on_sale==1?'在售':'下架'?></td>
        <td><?=\backend\models\Goods::$staOptions[$model->status]?></td>
        <td><?=$model->sort?></td>
        <td><?=date('Y-m-d H:i:s',$model->create_time)?></td>
        <td>
            <?php
            if(Yii::$app->user->can('goods/gallery')) {
            echo \yii\bootstrap\Html::a('相册',['goods/gallery','id'=>$model->id],['class'=>'btn btn-danger btn-xs']);}?>
            <?php
            if(Yii::$app->user->can('goods/edit')) {
            echo \yii\bootstrap\Html::a('修改',['goods/edit','id'=>$model->id],['class'=>'btn btn-info btn-xs']);}?>
            <?php
            if(Yii::$app->user->can('goods/del')) {
            echo \yii\bootstrap\Html::a('删除',['goods/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs']);}?>

        </td>
        </tr>

    <?php endforeach; ?>
</table>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',

]);