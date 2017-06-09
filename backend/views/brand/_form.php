<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use xj\uploadify\Uploadify;
/* @var $this yii\web\View */
/* @var $model backend\models\Brand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="brand-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'intro')->textarea(['rows' => 6]) ?>
    <?= $form->field($model,'logo')->hiddenInput();?>
    <?= Html::fileInput('test', NULL, ['id' => 'test']);?>
    <?= Uploadify::widget([
        'url' => yii\helpers\Url::to(['s-upload']),
        'id' => 'test',
        'csrf' => true,
        'renderTag' => false,
        'jsOptions' => [
            'width' => 120,
            'height' => 40,
            'onUploadError' => new JsExpression(<<<EOF
            function(file, errorCode, errorMsg, errorString) {
                console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
            }
EOF
            ),
    'onUploadSuccess' => new JsExpression(<<<EOF
    function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
         //将上传成功后的图片地址(data.fileUrl)写入img标签
        $("#img_logo").attr("src",data.fileUrl).show();
        //将上传成功后的图片地址(data.fileUrl)写入logo字段
        $("#brand-logo").val(data.fileUrl);
    }
}
EOF
    ),
    ]
    ]);
    ?>
   <?php
   if($model->logo){
    echo \yii\helpers\Html::img('@web'.$model->logo);
    }else{
    echo \yii\helpers\Html::img('',['style'=>'display:none','id'=>'img_logo','height'=>'50']);
    }
   ?>
    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'status')->radioList(\backend\models\Brand::$sexOptions) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
