<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%project_team}}`.
 */
class m230217_165242_create_project_team_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%project_team}}', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer()->notNull()->comment('ИД проекта'),
            'user_id' => $this->integer()->notNull()->comment('ИД пользователя'),
            'type' => $this->smallInteger()->notNull()->defaultValue(1)->comment('type'),
            'role' => $this->string(100)->null()->comment('Роль в проекте'),
        ]);

        $this->addForeignKey('fk_project_team_project_id', 'project_team', 'project_id', 'project', 'id', 'cascade', 'cascade');
        $this->createIndex('idx_project_team_project_id', 'project_team', 'project_id');

        $this->addForeignKey('fk_project_team_user_id', 'project_team', 'user_id', 'user', 'id', 'cascade', 'cascade');
        $this->createIndex('idx_project_team_user_id', 'project_team', 'user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%project_team}}');
    }
}
