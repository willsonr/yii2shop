<?php
use yii\widgets\ActiveForm;
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'password_hash')->passwordInput();
echo $form->field($model,'password_hash1')->passwordInput();
echo $form->field($model,'password_hash2')->passwordInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();