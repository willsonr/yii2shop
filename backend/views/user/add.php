<?php
use yii\widgets\ActiveForm;
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password_hash')->passwordInput();
echo $form->field($model,'email')->textInput();
echo $form->field($model,'status')->radioList(\backend\models\User::$staOptions);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();