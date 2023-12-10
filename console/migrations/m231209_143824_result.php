<?php

use yii\db\Migration;

/**
 * Class m231209_143824_result
 */
class m231209_143824_result extends Migration
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
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('result', [
            'id' => $this->primaryKey(),
            'skill' => $this->string(300)->notNull()
        ], $tableOptions);
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231209_143824_result cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231209_143824_result cannot be reverted.\n";

        return false;
    }
    */
}
