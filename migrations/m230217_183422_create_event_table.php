<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event}}`.
 */
class m230217_183422_create_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%event}}', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer()->notNull()->comment('Проект'),
            'name' => $this->string()->notNull()->comment('Наименование'),
            'date_plan' => $this->date()->notNull()->comment('Дата завершения'),
            'is_complete' => $this->boolean()->notNull()->defaultValue(false)->comment('Завершена')
        ]);

        $this->addForeignKey('fk_event_project_id', 'event', 'project_id', 'project', 'id', 'cascade', 'cascade');
        $this->createIndex('idx_event_project_id', 'event', 'project_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%event}}');
    }
}
