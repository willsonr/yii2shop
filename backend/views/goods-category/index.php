<?php
if(Yii::$app->user->can('goods-category/add')) {
echo \yii\bootstrap\Html::a('添加',['goods-category/add'],['class'=>'btn btn-info btn-xs']);}?>
<table class="list table table-hover table-bordered">
    <tr class="danger">
        <th>ID</th>
        <th>名称</th>
        <th>分类ID</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model): ?>
        <tr data-lft="<?=$model->lft?>" data-rgt="<?=$model->rgt?>" data-tree="<?=$model->tree?>">
            <td><?=$model->id?></td>
            <td><?=str_repeat(' - ',$model->depth).$model->name?>
            <span class="dianji glyphicon glyphicon-chevron-down" style="float: right"></span>
            </td>
            <td><?=$model->parent_id?$model->parent->name:''?></td>
            <td>
                <?php
                if(Yii::$app->user->can('goods-category/edit')) {
                echo \yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$model->id],['class'=>'btn btn-info btn-xs']);}?>
                <?php
                if(Yii::$app->user->can('goods-category/del')) {
                echo \yii\bootstrap\Html::a('删除',['goods-category/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs']);}?>
            </td>
        </tr>

    <?php endforeach; ?>
</table>
<?php
$js=<<<JS
    $('.dianji').click(function () {
        var tr=$(this).closest('tr');
        var tree=parseInt(tr.attr('data-tree'));
        var lft=parseInt(tr.attr('data-lft'));
        var rgt=parseInt(tr.attr('data-rgt'));
        var show=$(this).hasClass('glyphicon-chevron-up');
           //qiehuantubiao
        $(this).toggleClass('glyphicon-chevron-up');
        $(this).toggleClass('glyphicon-chevron-down');
        $('.list tr').each(function () {


           if($(this).attr('data-tree')==tree && $(this).attr('data-lft')>lft && $(this).attr('data-rgt')<rgt){
             show?$(this).fadeIn():$(this).fadeOut();
           }

        });
    })
JS;
$this->registerJs($js);
