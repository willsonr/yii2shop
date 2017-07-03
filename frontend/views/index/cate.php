<?php
use yii\helpers\Html;
?>


<div class="category fl cat1"> <!-- 非首页，需要添加cat1类 -->
    <div class="cat_hd off">  <!-- 注意，首页在此div上只需要添加cat_hd类，非首页，默认收缩分类时添加上off类，并将cat_bd设置为不显示，鼠标滑过时展开菜单则将off类换成on类 -->
        <h2>全部商品分类</h2>
        <em></em>
    </div>

    <div class="cat_bd none">
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

</div>