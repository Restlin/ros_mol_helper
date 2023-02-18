<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%project}}`.
 */
class m230217_151040_create_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%project}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(200)->notNull()->comment('Наименование'),
            'level' => $this->smallInteger()->notNull()->defaultValue(1)->comment('Масштаб реализации'),
            'date_start' => $this->date()->null()->comment('Дата начала'),
            'date_end' => $this->date()->null()->comment('Дата окончания проекта'),
            'author_id' => $this->integer()->null()->comment('Автор'),
        ]);

        $this->addForeignKey('fk_project_author_id', 'project', 'author_id', 'user', 'id', 'cascade', 'cascade');
        $this->createIndex('idx_project_author_id', 'project', 'author_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%project}}');
    }
}
