

<div style="float: right">
<?=\yii\bootstrap\Html::a('安全退出',['user/logout'],['class'=>'btn btn-danger btn-xs'])?>
</div>
<div>
    <?=\yii\bootstrap\Html::a('添加',['user/add'],['class'=>'btn btn-info btn-xs'])?>
</div>

<table class="list table table-hover table-bordered">
    <tr class="danger">
        <th>ID</th>
        <th>用户名</th>
        <th>状态</th>
        <th>注册时间</th>
        <th>最后登录时间</th>
        <th>最后登录IP</th>
        <th>操作</th>

    </tr>
    <?php foreach ($models as $model): ?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->username?></td>
            <td><?=\backend\models\User::$staOptions[$model->status]?></td>
            <td><?=date('Y-m-d H:i:s',$model->created_at)?></td>
            <td><?=date('Y-m-d H:i:s',$model->updated_at)?></td>
            <td><?=$model->update_ip?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['user/edit','id'=>$model->id],['class'=>'btn btn-info btn-xs'])?>
                <?=\yii\bootstrap\Html::a('修改密码',['user/update','id'=>$model->id],['class'=>'btn btn-info btn-xs'])?>
                <?=\yii\bootstrap\Html::a('删除',['user/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs'])?>

            </td>
        </tr>

    <?php endforeach; ?>
</table>
