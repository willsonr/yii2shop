<?=\yii\bootstrap\Html::a('添加',['goods-category/add'],['class'=>'btn btn-info btn-xs'])?>
<table class="table table-hover table-bordered">
    <tr class="danger">
        <th>ID</th>
        <th>名称</th>
        <th>分类ID</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model): ?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->parent_id?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$model->id],['class'=>'btn btn-info btn-xs'])?>
                <?=\yii\bootstrap\Html::a('删除',['goods-category/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs'])?>
            </td>
        </tr>

    <?php endforeach; ?>
</table>

