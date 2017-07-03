<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $username
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $address
 * @property string $tel
 * @property integer $status
 * @property integer $member_id
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }
    public $id;
    public function makeDefault(){
        $this->member_id = \Yii::$app->user->id;
        //如果有设置默认收货地址,则查找该用户所有的地址,status=0
        if($this->status){
            $models = Address::find()->where(['member_id'=>$this->member_id])->all();
            foreach ($models as $model){
                $model->status = 0;
                $model->save(false);
            }
        }
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'member_id'], 'integer'],
            [['username', 'province', 'city', 'area', 'address'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '收货人：',
            'province' => '所在地区：',
            'city' => '所在地区',
            'area' => '所在地区',
            'address' => '详细地址：',
            'tel' => '手机号码：',
            'status' => '设为默认',
            'member_id' => 'Member ID',
        ];
    }


    public function getProvinces(){
        return $this->hasOne(Locations::className(),['id'=>'province']);
    }
    public function getCitys(){
        return $this->hasOne(Locations::className(),['id'=>'city']);
    }
    public function getAreas(){
        return $this->hasOne(Locations::className(),['id'=>'area']);
    }
}
