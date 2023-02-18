<?php

use yii\db\Migration;

/**
 * Class m230218_114506_add_status_to_project
 */
class m230218_114506_add_status_to_project extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('project', 'status', $this->smallInteger()->notNull()->defaultValue(1)->comment('Статус'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230218_114506_add_status_to_project cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230218_114506_add_status_to_project cannot be reverted.\n";

        return false;
    }
    */
}
