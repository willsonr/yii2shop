<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "locations".
 *
 * @property string $id
 * @property string $name
 * @property string $parent_id
 * @property integer $level
 */
class Locations extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     *
     */

    public static function tableName()
    {
        return 'locations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent_id', 'level'], 'required'],
            [['parent_id', 'level'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '请选择地区',
            'parent_id' => 'Parent ID',
            'level' => 'Level',
            'province'=>'请选择地区',
            'city'=>'请选择地区',
            'area'=>'请选择地区'
        ];
    }


}


