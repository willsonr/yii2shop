
<form action="<?=\yii\helpers\Url::to(['index/save'])?>"  method="post">
<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php if(!\frontend\models\Order::address()){} foreach (\frontend\models\Order::address() as $address):?>
                    <p>
                        <input type="radio" value="<?=$address['id']?>" name="address"/><?=$address['address']?>
                    </p>
                <?php endforeach;?>
            </div>


        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $yf=0; foreach(\frontend\models\Order::delivery() as $delivery):?>
                        <tr class="">
                            <td>
                                <label><input type="radio" name="delivery_id" value="<?=$delivery['delivery_id'] ?>" class="yf_<?=++$yf?>"/><?=$delivery['delivery_name']?></label>

                            </td>
                            <td>￥10.00</td>
                            <td><?=$delivery['delivery_detail']?></td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <?php foreach(\frontend\models\Order::payment() as $payment):?>
                        <tr class="">
                            <td class="col1"><label><input type="radio" name="payment_id" value="<?=$payment['payment_id']?>" /><?=$payment['payment_name']?></label></td>
                            <td class="col2"><?=$payment['payment_detail']?></td>
                        </tr>
                    <?php endforeach;?>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
        <div class="receipt none">
            <h3>发票信息 </h3>


            <div class="receipt_select ">
                <form action="">
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>

            </div>
        </div>
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php $count=$total=null;
                foreach ($goods as $goods_one):?>

                    <tr>
                        <td class="col1"><a href=""><img src="<?='http://admin.yii2shop.com'.$goods_one['logo']?>" alt="" /></a>  <strong><a href=""><?=$goods_one['name']?></a></strong></td>
                        <td class="col3">￥<?=$goods_one['shop_price']?></td>
                        <td class="col4"><?=$goods_one['amount'];$count+=$goods_one['amount'];?></td>
                        <td class="col5"><span>￥<?=$goods_one['shop_price']*$goods_one['amount'];$total+=$goods_one['shop_price']*$goods_one['amount']?></span></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span><?=$count?> 件商品，总商品金额：</span>
                                <em>￥<span id="total"><?=$total?></span></em>
                            </li>

<!--                            <li>-->
<!--                                <span>返现：</span>-->
<!--                                <em>-￥240.00</em>-->
<!--                            </li>-->
                            <li>
                                <span>运费：</span>
                                <em> ￥<span id="yf">0</span></em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em>￥<span class="total_money"><?=$total?></span></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>
    <input type="hidden" name="total" value="<?=$total?>">

    <div class="fillin_ft">
        <input name="_csrf-frontend" type="hidden" id="_csrf-frontend" value="<?= Yii::$app->request->csrfToken ?>">
        <span><input type="submit" value="提交订单"/></span>

        <p>应付总额：<strong>￥<span class="total_money"><?=$total?></span></strong></p>

    </div>

</div>
</form>
<!-- 主体部分 end -->

<?php
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    var total = parseInt($('#total').text());
   $('.yf_1,.yf_4').click(function(){
       if($(this).prop('checked')){
           if($('#total').text()<=499){
               //alert('aaa');
              $('#yf').text(15);
           }else{
                $('#yf').text(10);
           }
           var money =   parseInt($('#yf').text());
           
           $('.total_money').text(total+money);
       }
   });
   
    $('.yf_2,.yf_3').click(function(){
       if($(this).prop('checked')){
           if($('#total').text()<=499){
             //  alert('aaa');
              $('#yf').text(40);
           }else{
                $('#yf').text(10);
           }
           var money =   parseInt($('#yf').text());
           
           $('.total_money').text(total+money);
       }
   })
   
   

    
JS

))
?>