<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "help_message".
 *
 * @property int $id
 * @property string $model Сущность
 * @property string $attr Поле
 * @property string $content Подсказка
 */
class HelpMessage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'help_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model', 'attr', 'content'], 'required'],
            [['model', 'attr', 'content'], 'string', 'max' => 100],
            [['model', 'attr'], 'unique', 'targetAttribute' => ['model', 'attr']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Код',
            'model' => 'Сущность',
            'attr' => 'Поле',
            'content' => 'Подсказка',
        ];
    }

    public static function getModelList(): array {
        return [
            'Project' => 'Проект',
            'ProjectTeam' => 'Команда',
            'ProjectResult' => 'Результат',
            'Task' => 'Задача',
            'Event' => 'Мероприятие',
            'Publication' => 'Публикация',
            'User' => 'Пользователь'
        ];
    }

    public static function getAttrList(): array {
        return [
            'Project' => [
                'id' => 'Код',
                'name' => 'Наименование',
                'level' => 'Масштаб реализации',
                'date_start' => 'Дата начала',
                'date_end' => 'Дата окончания',
                'author_id' => 'Автор',
            ],
            'ProjectTeam' => [
                'id' => 'Код',
                'project_id' => 'Проект',
                'user_id' => 'Пользователь',
                'type' => 'Тип',
                'role' => 'Роль в проекте',
            ],
            'ProjectResult' => [
                'id' => 'Код',
                'project_id' => 'ИД проекта',
                'events' => 'Кол-во мероприятий',
                'men' => 'Кол-во участников',
                'publications' => 'Кол-во публикаций',
                'views' => 'Кол-во просмотров',
                'effect' => 'Социальный эффект',
            ],
            'Task' => [
                'id' => 'Код',
                'project_id' => 'Проект',
                'name' => 'Наименование',
                'user_id' => 'Исполнитель',
                'date_plan' => 'Дата завершения',
                'is_complete' => 'Завершена',
            ],
            'Event' => [
                'id' => 'Код',
                'project_id' => 'Проект',
                'name' => 'Наименование',
                'date_plan' => 'Дата завершения',
                'is_complete' => 'Завершена',
            ],
            'Publication' => [
                'id' => 'Код',
                'event_id' => 'Событие',
                'type' => 'Тип',
                'date_in' => 'Дата',
                'link' => 'Ссылка',
                'views' => 'Кол-во просмотров',
            ],
            'User' => [
                'id' => 'Код',
                'email' => 'Email',
                'fio' => 'ФИО',
                'tg_id' => 'TG id',
                'password_hash' => 'Хеш пароля',
                'password' => 'Пароль',
                'role' => 'Роль',
                'photo' => 'Фото',
            ],
        ];
    }

    public static function getAllAttrList(): array {
        $data = self::getAttrList();
        $result = [];
        foreach($data as $model => $attrs) {
            $result = array_merge($result, $attrs);
        }
        return $result;
    }

    public static function getList(): array {
        $models = self::find()->all();
        $list = [];
        foreach($models as $model) {
            $list["$model->model[$model->attr]"] = $model->content;
        }
        return $list;
    }
}
