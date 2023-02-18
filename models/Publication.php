<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "publication".
 *
 * @property int $id
 * @property int $event_id Событие
 * @property int $type Тип
 * @property string $date_in Дата
 * @property string|null $link Ссылка
 * @property int|null $views Кол-во просмотров
 *
 * @property Event $event
 * @property Project $project
 */
class Publication extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'publication';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $types = self::getTypeList();
        return [
            [['event_id', 'type', 'date_in'], 'required'],
            [['event_id', 'type', 'views'], 'default', 'value' => null],
            [['event_id', 'type', 'views'], 'integer'],
            [['type'], 'in', 'range' => array_keys($types)],
            [['date_in'], 'safe'],
            [['link'], 'string', 'max' => 100],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Event::class, 'targetAttribute' => ['event_id' => 'id']],
        ];
    }

    public function afterSave($insert, $changedAttributes) {
        $user = Yii::$app->user->getIdentity()->user;
        $content = $insert ? "Добавлена публикация со сроком {$this->date_in} и ссылкой: $this->link" : "Изменена публикация со сроком {$this->date_in} и ссылкой: $this->link";
        Log::add($this->project, $user, $content);
        if($insert) {
            foreach($this->project->users as $user) {
                Notice::add($this->project, $user, "В проекте {$this->project->name} добавлена публикация {$this->link}");
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => 'Событие',
            'type' => 'Тип',
            'date_in' => 'Дата',
            'link' => 'Ссылка',
            'views' => 'Кол-во просмотров',
        ];
    }

    /**
     * Gets query for [[Event]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::class, ['id' => 'event_id']);
    }

    /**
     * Gets query for [[Event]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'project_id'])->via('event');
    }

    public static function getTypeList(): array {
        return [
            1 => 'Печатное  издание',
            2 => 'Социальные сети',
            3 => 'Видеохостинги',
            4 => 'Нативная реклама',
            5 => 'TV ресурсы',
            6 => 'Иное',
        ];
    }
}
