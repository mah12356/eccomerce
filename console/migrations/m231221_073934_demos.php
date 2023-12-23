<?php

use yii\db\Migration;

/**
 * Class m231221_073934_demos
 */
class m231221_073934_demos extends Migration
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

        $this->createTable('demos', [
            'id' => $this->primaryKey(),
            'description' => $this->text()->notNull(),
            'video' => $this->string(300)->notNull(),
            'for' => $this->string(300)->notNull()
        ], $tableOptions);
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231221_073934_demos cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231221_073934_demos cannot be reverted.\n";

        return false;
    }
    */
}
