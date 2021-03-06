<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $label
 * @property string $url
 * @property integer $parent_id
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }
    static public function Parent(){
        $model=self::find()->all();
        array_unshift($model,['label'=>'顶级分类','id'=>0]);
        return ArrayHelper::map($model,'id','label');
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label','parent_id'], 'required'],
            [['parent_id', 'sort'], 'integer'],
            [['label'], 'string', 'max' => 20],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => '名称',
            'url' => '地址/路由',
            'parent_id' => '上级菜单',
            'sort' => '排序',
        ];
    }
//    public static function getPermissionOption(){
//        $authManager=\Yii::$app->authManager;
//        return ArrayHelper::map($authManager->getPermissions(),'name','description');
//    }

    //获取子菜单
    public function getChildren()
    {
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}
