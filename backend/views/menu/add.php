<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'label');
echo $form->field($model,'url')->textInput();
echo $form->field($model,'parent_id')->dropDownList(\backend\models\Menu::Parent(),['prompt'=>'=请选择分类=']);
echo $form->field($model,'sort')->textInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();