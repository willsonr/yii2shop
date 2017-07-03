
<!-- 页面主体 start -->
<div class="main w1210 bc mt10">
    <div class="crumb w1210">
        <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
    </div>

    <!-- 左侧导航菜单 start -->
    <div class="menu fl">
        <h3>我的XX</h3>
        <div class="menu_wrap">
            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                <dd><b>.</b><a href="">账户余额</a></dd>
                <dd><b>.</b><a href="">消费记录</a></dd>
                <dd><b>.</b><a href="">我的积分</a></dd>
                <dd><b>.</b><a href="">收货地址</a></dd>
            </dl>

            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">返修/退换货</a></dd>
                <dd><b>.</b><a href="">取消订单记录</a></dd>
                <dd><b>.</b><a href="">我的投诉</a></dd>
            </dl>
        </div>
    </div>
    <!-- 左侧导航菜单 end -->

    <!-- 右侧内容区域 start -->
    <div class="content fl ml10">
        <div class="address_hd">
            <h3>收货地址薄</h3>
            <?php $addresses = \frontend\models\Address::find()->where(['member_id'=>Yii::$app->user->id])->all();

            $count = 0;
            foreach ($addresses as $address){
                $province = \frontend\models\Locations::findOne(['id'=>$address->province])->name;
                $city = \frontend\models\Locations::findOne(['id'=>$address->city])->name;
                $area = \frontend\models\Locations::findOne(['id'=>$address->area])->name;


                echo '<dl>';
                echo '<dt>'.++$count.'&nbsp;'.$address->username.'&nbsp;'.$province.'&nbsp;'.$city.'&nbsp;'.$area.'&nbsp;'.$address->tel.'</dt>';
                echo '<dd>' ;
                echo \yii\bootstrap\Html::a('修改',['address/edit','id'=>$address->id]).'&nbsp;';
                echo \yii\bootstrap\Html::a('删除',['address/del','id'=>$address->id]).'&nbsp;';
                echo '<a href="">设为默认地址</a>&nbsp;';
                echo '</dd>';
                echo '</dl>';
            }
            ?>
        </div>

        <div class="address_bd mt10">
            <h4>新增收货地址</h4>
            <?php

            $form=\yii\widgets\ActiveForm::begin([
                'fieldConfig'=>['options'=>[
                    'tag'=>'li'],
                    ],

                'action'=>['address/index'],
            ]);
             echo '<ul>';
            echo $form->field($model,'username')->textInput(['class'=>'txt']);

            echo '<li><label for="">所在地区：</label>';
            echo $form->field($model,'province',['template' => "{input}",'options'=>['tag'=>false]])->dropDownList([''=>'=选择省='],['id'=>'s1']);
            echo $form->field($model,'city',['template' => "{input}",'options'=>['tag'=>false]])->dropDownList([''=>'=选择市='],['id'=>'s2']);
            echo $form->field($model,'area',['template' => "{input}",'options'=>['tag'=>false]])->dropDownList([''=>'=选择县='],['id'=>'s3']);
            echo '</li>';
            echo $form->field($model,'address')->textInput(['class'=>'txt']);
            echo $form->field($model,'tel')->textInput(['class'=>'txt']);
            echo $form->field($model,'status')->checkbox(['class'=>'check']);
            echo '<li>';
            echo ' <label for="">&nbsp;</label>' ;
            echo '<input type="submit" name="" class="btn" value="保存" />'  ;
            echo '</li>';
            \yii\widgets\ActiveForm::end();
            echo '</ul>';
            ?>
        </div>

    </div>
    <!-- 右侧内容区域 end -->
</div>
<!-- 页面主体 end-->
<?php
$url = \yii\helpers\Url::to(['province']);
$area = \yii\helpers\Url::to(['area']);
$js =<<<JS
$.getJSON("{$url}",'',function(response){
        //获得所有数据,遍历出来
        console.debug(response);
        $.each(response,function(i,v){
           var html = '<option value="'+v.id+'">'+v.name+'</option>';
           $(html).appendTo($('#s1'));
        })
      
    },'json');

       //当选了省,传对应的value 通过json 读取对应的二级数据
        $('#s1').on('change',function(){
            //清理二级,三级的数据
            $('select:eq(1)').get(0).length = 1;
            $('select:eq(2)').get(0).length = 1;
            var id = $(this).val();
            $.post("{$area}",{'id':id},function(response){
                $.each(response,function(i,v){
                     var html = '<option value="'+v.id+'">'+v.name+'</option>';
                     $(html).appendTo($('#s2'));
                });
            },'json');
        });
      //区县
      $('#s2').on('change',function(){
          var id = $(this).val();
          //$('select:eq(1)').get(0).length = 1;
            $.post("{$area}",{'id':id},function(response){
                $.each(response,function(i,v){
                     var html = '<option value="'+v.id+'">'+v.name+'</option>';
                     $(html).appendTo($('#s3'));
                });
            },'json');
      })






JS;
$this->registerJs($js);
$jss = '';
if($model->province){
    $jss .= '$("#address-province").val("'.$model->province.'");';
}
if($model->city){
    $jss .= '$("#address-province").change();$("#address-city").val("'.$model->city.'");';
}
if($model->area){
    $jss .= '$("#address-city").change();$("#address-area").val("'.$model->area.'");';
}
$this->registerJs($jss);