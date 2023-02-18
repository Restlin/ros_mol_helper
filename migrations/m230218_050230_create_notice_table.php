<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notice}}`.
 */
class m230218_050230_create_notice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notice}}', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer()->notNull()->comment('Проект'),
            'user_id' => $this->integer()->notNull()->comment('ИД пользователя'),
            'date_in' => $this->datetime()->notNull()->comment('Время сообщения'),
            'content' => $this->text()->notNull()->comment('Содержание'),
            'is_viewed' => $this->boolean()->notNull()->defaultValue(false)->comment('Просмотрено'),
        ]);

        $this->addForeignKey('fk_notice_project_id', 'notice', 'project_id', 'project', 'id', 'cascade', 'cascade');
        $this->createIndex('idx_notice_project_id', 'notice', 'project_id');

        $this->addForeignKey('fk_notice_user_id', 'notice', 'user_id', 'user', 'id', 'cascade', 'cascade');
        $this->createIndex('idx_notice_user_id', 'notice', 'user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%notice}}');
    }
}
