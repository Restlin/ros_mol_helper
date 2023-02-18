<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project_result".
 *
 * @property int $id
 * @property int $project_id ИД проекта
 * @property int|null $events Кол-во мероприятий
 * @property int|null $men Кол-во участников
 * @property int|null $publications Кол-во участников
 * @property int|null $views Кол-во просмотров
 * @property string|null $effect Социальный эффект
 *
 * @property Project $project
 */
class ProjectResult extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project_result';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id'], 'required'],
            [['project_id', 'events', 'men', 'publications', 'views'], 'default', 'value' => null],
            [['project_id', 'events', 'men', 'publications', 'views'], 'integer'],
            [['effect'], 'string', 'max' => 200],
            [['project_id'], 'unique'],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::class, 'targetAttribute' => ['project_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'ИД проекта',
            'events' => 'Кол-во мероприятий',
            'men' => 'Кол-во участников',
            'publications' => 'Кол-во публикаций',
            'views' => 'Кол-во просмотров',
            'effect' => 'Социальный эффект',
        ];
    }

    public function afterSave($insert, $changedAttributes) {
        $user = Yii::$app->user->getIdentity()->user;
        $content = $insert ? "Добавлены результаты проекта: мероприятия - {$this->events}, участники - {$this->men}, публикации - {$this->publications}, просмотры - {$this->views}, эффект - {$this->effect}" :
                "Изменены результаты проекта: мероприятия - {$this->events}, участники - {$this->men}, публикации - {$this->publications}, просмотры - {$this->views}, эффект - {$this->effect}";
        Log::add($this->project, $user, $content);
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Gets query for [[Project]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'project_id']);
    }
}
