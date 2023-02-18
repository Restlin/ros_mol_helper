<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m230217_141703_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string(100)->notNull()->unique()->comment('Email'),
            'fio' => $this->string(50)->notNull()->comment('ФИО'),
            'tg_id' => $this->bigInteger()->null()->unique()->comment('TG id'),
            'password_hash' => $this->string(100)->notNull()->comment('Хеш пароля'),
            'role' => $this->smallInteger()->notNull()->defaultValue(1)->comment('Роль'),
            'photo' => $this->binary()->null()->comment('Фото'),
        ]);

        $this->createIndex('idx_user_email', 'user', 'email', true);
        $this->createIndex('idx_user_tg_id', 'user', 'tg_id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}

