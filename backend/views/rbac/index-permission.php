
<?php
if(Yii::$app->user->can('rbac/add-permission')) {
echo \yii\bootstrap\Html::a('添加',['rbac/add-permission'],['class'=>'btn btn-info btn-xs']);}?>
<table class="table table-hover table-bordered">
    <tr>
        <th>权限名称</th>
        <th>权限介绍</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model): ?>
    <tr>
        <td><?=$model->name?></td>
        <td><?=$model->description?></td>
        <td>
            <?php
            if(Yii::$app->user->can('rbac/edit-permission')) {
            echo \yii\bootstrap\Html::a('修改',['rbac/edit-permission','name'=>$model->name],['class'=>'btn btn-warning btn-xs']);}?>
            <?php
            if(Yii::$app->user->can('rbac/del-permission')) {
            echo \yii\bootstrap\Html::a('删除',['rbac/del-permission','name'=>$model->name],['class'=>'btn btn-danger btn-xs']);}?>
        </td>
    </tr>

    <?php endforeach; ?>
</table>