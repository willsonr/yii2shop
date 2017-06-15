<div class="site-login">
<h1>登录</h1>
    <div class="row">
        <div class="col-lg-5">
<?php $form = \yii\bootstrap\ActiveForm::begin();?>
    <?php echo $form->field($model,'username')->textInput();?>
    <?php echo $form->field($model,'password')->passwordInput();?>
<!--//验证码-->
    <?php echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),['captchaAction'=>'user/captcha',
    'template'=>'<div class="row"><div class="col-lg-4">{input}</div><div class="col-lg-1">{image}</div></div>'
])->label('验证码');?>
    <?php echo $form->field($model,'rememberMe')->checkbox();?>
    <?php echo \yii\bootstrap\Html::submitButton('确认',['class'=>'btn btn-info']);?>
    <?php \yii\bootstrap\ActiveForm::end();?>
        </div>
    </div>
</div>
