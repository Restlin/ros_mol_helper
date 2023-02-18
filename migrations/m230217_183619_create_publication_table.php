<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%publication}}`.
 */
class m230217_183619_create_publication_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%publication}}', [
            'id' => $this->primaryKey(),
            'event_id' => $this->integer()->notNull()->comment('Событие'),
            'type' => $this->smallInteger()->notNull()->comment('Тип'),
            'date_in' => $this->date()->notNull()->comment('Дата'),
            'link' => $this->string(100)->null()->comment('Ссылка'),
            'views' => $this->integer()->null()->comment('Кол-во просмотров')
        ]);

        $this->addForeignKey('fk_publication_event_id', 'publication', 'event_id', 'event', 'id', 'cascade', 'cascade');
        $this->createIndex('idx_publication_event_id', 'publication', 'event_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%publication}}');
    }
}
