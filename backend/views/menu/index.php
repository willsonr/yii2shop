
<?=\yii\bootstrap\Html::a('添加',['menu/add'],['class'=>'btn btn-info btn-xs'])?>
<table class="table table-hover">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>地址/路由</th>
        <th>上级分类</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->label?></td>
            <td><?=$model->url?></td>
            <td><?=$model->parent_id?></td>
            <td><?=$model->sort?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['menu/edit','id'=>$model->id],['class'=>'btn btn-info btn-xs'])?>
                <?=\yii\bootstrap\Html::a('删除',['menu/del','id'=>$model->id],['class'=>'btn btn-info btn-xs'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
