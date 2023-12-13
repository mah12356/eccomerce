<?php

use yii\db\Migration;

/**
 * Class m231213_135202_counseling
 */
class m231213_135202_counseling extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql'){
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('counseling', [
            'id' => $this->primaryKey(),
            'name' => $this->string(300)->notNull(),
            'phone' => $this->integer(225)->notNull()
        ], $tableOptions);
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231213_135202_counseling cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231213_135202_counseling cannot be reverted.\n";

        return false;
    }
    */
}
