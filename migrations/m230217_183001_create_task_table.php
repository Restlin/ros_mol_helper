<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task}}`.
 */
class m230217_183001_create_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task}}', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer()->notNull()->comment('Проект'),
            'name' => $this->string()->notNull()->comment('Наименование'),
            'user_id' => $this->integer()->null()->comment('Исполнитель'),
            'date_plan' => $this->date()->notNull()->comment('Дата завершения'),
            'is_complete' => $this->boolean()->notNull()->defaultValue(false)->comment('Завершена')
        ]);

        $this->addForeignKey('fk_task_project_id', 'task', 'project_id', 'project', 'id', 'cascade', 'cascade');
        $this->createIndex('idx_task_project_id', 'task', 'project_id');

        $this->addForeignKey('fk_task_user_id', 'task', 'user_id', 'user', 'id', 'cascade', 'cascade');
        $this->createIndex('idx_task_user_id', 'task', 'user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%task}}');
    }
}
