<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%log}}`.
 */
class m230218_103543_create_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%log}}', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer()->notNull()->comment('Проект'),
            'user_id' => $this->integer()->notNull()->comment('Пользователь'),
            'date_in' => $this->datetime()->notNull()->comment('Время'),
            'content' => $this->text()->notNull()->comment('Изменение'),
        ]);

        $this->addForeignKey('fk_log_project_id', 'log', 'project_id', 'project', 'id', 'cascade', 'cascade');
        $this->createIndex('idx_log_project_id', 'log', 'project_id');

        $this->addForeignKey('fk_log_user_id', 'log', 'user_id', 'user', 'id', 'cascade', 'cascade');
        $this->createIndex('idx_log_user_id', 'log', 'user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%log}}');
    }
}
