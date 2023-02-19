<?php

use yii\db\Migration;

/**
 * Class m230219_031913_add_about_url_to_user
 */
class m230219_031913_add_about_url_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'about', $this->text()->null()->comment('О себе'));
        $this->addColumn('user', 'url', $this->string(200)->null()->comment('Ссылка на резюме'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'about');
        $this->dropColumn('user', 'url');
    }    
}
