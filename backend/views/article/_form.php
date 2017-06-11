<?php
use yii\helpers\ArrayHelper;
//use backend\models\Article;
$date=\backend\models\ArticleCategory::find()->all();
$form=\yii\bootstrap\ActiveForm::begin();//表单开始
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
$date=ArrayHelper::map($date,'id','name');
echo $form->field($model,'article_category_id')->dropDownList($date,['prompt'=>'请选择分类']);
echo $form->field($model,'sort')->textInput();
//echo $form->field($content,'content')->textarea();
echo $form->field($content,'content')->widget('kucha\ueditor\UEditor',[]);
echo $form->field($model,'status')->radioList(\backend\models\Article::$sexOptions);
echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();//表单结束