




<?=\yii\bootstrap\Html::a('返回',['article/index'],['class'=>'btn btn-xs'])?>
<div class="panel panel-danger ">
    <div class="panel-heading text-center">
        <h3 class="panel-title"><?=$models->name?></h3>
    </div>
    <div class="panel-body">
        摘要：<?=$models->intro?>
    </div>
    <div class="panel-footer">
        <?=$content->content?>
    </div>
    <div class="panel-body">
        <?=date('Y-m-d H:i:s',$models->create_time)?>
    </div>
</div>




