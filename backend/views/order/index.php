
<h1>订单列表</h1>
<table class="table table-hover">
    <tr>
        <th>ID</th>
        <th>用户</th>
        <th>姓名</th>
        <th>地址</th>
        <th>电话</th>
        <th>快递方式</th>
        <th>付款方式</th>
        <th>总价</th>
        <th>状态</th>
        <th>订单创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=\Yii::$app->user->identity->username?></td>
            <td><?=$model->name?></td>
            <td><?=$model->province?><?=$model->city?><?=$model->area?><?=$model->address?></td>
            <td><?=$model->tel?></td>
            <td><?=$model->delivery_name?></td>
            <td><?=$model->payment_name?></td>
            <td><?=$model->total?></td>
            <td><?=$model->status==1?'未发货':'已发货'?></td>
            <td><?=date('Y-m-d H:i:s',$model->create_time)?></td>
            <td>
                <?=\yii\bootstrap\Html::a('发货',['order/edit','id'=>$model->id],['class'=>'btn btn-info btn-xs'])?>
                <?=\yii\bootstrap\Html::a('删除',['order/del','id'=>$model->id],['class'=>'btn btn-info btn-xs'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',

]);