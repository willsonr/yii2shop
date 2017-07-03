<div class="cat_bd none">
<?php
use yii\helpers\Html;
?>
    <?php foreach($goods as $k=>$good):?>
        <div class="cat <?=$k==0?"item1":""?>">
            <h3><?=Html::a($good->name,['index/cate','id'=>$good->id])?><b></b></h3>
            <div class="cat_detail none">
                <?php foreach ($good->children as $k2=>$child):?>
                    <dl <?=$k2==0?'class="dl_1st"':''?>>
                        <dt><?=Html::a($child->name,['index/cate','id'=>$child->id])?></dt>
                        <dd>
                            <?php foreach ($child->children as $cate)://循环遍历该二级分类的子分类（三级分类）?>

                                <?=Html::a($cate->name,['index/cate','id'=>$cate->id])?>
                            <?php endforeach;?>
                        </dd>
                    </dl>
                <?php endforeach;?>
            </div>
        </div>
    <?php endforeach;?>


</div>