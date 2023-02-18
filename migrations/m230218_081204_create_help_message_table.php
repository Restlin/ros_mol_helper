<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%help_message}}`.
 */
class m230218_081204_create_help_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%help_message}}', [
            'id' => $this->primaryKey(),
            'model' => $this->string(100)->notNull()->comment('Сущность'),
            'attr' =>  $this->string(100)->notNull()->comment('Поле'),
            'content' => $this->string(100)->notNull()->comment('Подсказка'),
        ]);

        $this->createIndex('idx_help_message_model_attr', 'help_message', 'model, attr', true);
        $this->batchInsert('help_message', ['model', 'attr', 'content'], [
            ['Project', 'name', 'Это наименование вашего проекта'],
            ['Project', 'level', 'Уровень масштаба вашего проекта'],
            ['Project', 'date_start', 'Когда фактически начнется ваш проект'],
            ['Project', 'date_end', 'Когда вы ожидаете завершения вашего проекта'],
            ['ProjectTeam', 'user_id', 'Участник, которого вы хотите пригласить в проект!'],
            ['ProjectTeam', 'role', 'Здесь указывается роль человека в проекте, какие он закрывает задачи'],
            ['ProjectResult', 'events', 'Количество мероприятий, которое нужно провести для выполнения проекта!'],
            ['ProjectResult', 'men', 'Количество участников, которые вы планируете привлечь к проекту до его завершения!'],
            ['ProjectResult', 'publications', 'Количество публикаций, которые вы планируете опубликовать до завершения проекта!'],
            ['ProjectResult', 'views', 'Количество просмотров, которое вы ожидаете достичь к завершению проекта!'],
            ['ProjectResult', 'effect', 'Эффект, который вы планируете достичь своим проектом'],
            ['Task', 'name', 'Наименование, формулировка задачи'],
            ['Task', 'user_id', 'Участник вашего проекта, кто будет отвечать за исполнение данной задачи'],
            ['Task', 'date_plan', 'Ожидаемая дата завершения задачи'],
            ['Task', 'is_complete', 'Отметье задачу, когда она будет завершена'],
            ['Publication', 'event_id', 'Выберите событие, о котором данная публикация'],
            ['Publication', 'type', 'Выберите тип публикации'],
            ['Publication', 'date_in', 'Укажите дату публикации'],
            ['Publication', 'link', 'Прямая ссылка на публикацию, если она у вас есть'],
            ['Publication', 'views', 'Укажите количество просмотров, которое достигла ваша публикация'],
        ]);            
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%help_message}}');
    }
}
