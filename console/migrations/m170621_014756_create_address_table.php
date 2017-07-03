<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170621_014756_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->comment('收货人'),
            'address'=>$this->string()->comment('详细地址'),
            'tel'=>$this->char('11')->comment('手机号码'),
            'status'=>$this->integer()->comment('状态')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
