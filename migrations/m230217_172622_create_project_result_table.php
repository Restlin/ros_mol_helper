<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%project_result}}`.
 */
class m230217_172622_create_project_result_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%project_result}}', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer()->notNull()->unique()->comment('ИД проекта'),
            'events' => $this->smallInteger()->null()->comment('Кол-во мероприятий'),
            'men' => $this->integer()->null()->comment('Кол-во участников'),
            'publications' => $this->integer()->null()->comment('Кол-во участников'),
            'views' => $this->integer()->null()->comment('Кол-во просмотров'),
            'effect'=> $this->string(200)->null()->comment('Социальный эффект')
        ]);

        $this->addForeignKey('fk_project_result_project_id', 'project_result', 'project_id', 'project', 'id', 'cascade', 'cascade');
        $this->createIndex('idx_project_result_project_id', 'project_result', 'project_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%project_result}}');
    }
}
