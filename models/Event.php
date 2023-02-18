<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event".
 *
 * @property int $id
 * @property int $project_id Проект
 * @property string $name Наименование
 * @property string $date_plan Дата завершения
 * @property bool $is_complete Завершена
 *
 * @property Project $project
 * @property Publication[] $publications
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'name', 'date_plan'], 'required'],
            [['project_id'], 'default', 'value' => null],
            [['project_id'], 'integer'],
            [['date_plan'], 'safe'],
            [['is_complete'], 'boolean'],
            [['name'], 'string', 'max' => 255],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::class, 'targetAttribute' => ['project_id' => 'id']],
        ];
    }

    public function afterSave($insert, $changedAttributes) {
        $user = Yii::$app->user->getIdentity()->user;
        $content = $insert ? "Создано новое мероприятие: $this->name с датой завершения: {$this->date_plan}" : "Изменено мероприятие: $this->name с датой завершения: {$this->date_plan}";
        if($this->is_complete) {
            $content = "$this->name с датой завершения: {$this->date_plan}";
        }        
        Log::add($this->project, $user, $content);
        if($insert) {
            foreach($this->project->users as $user) {
                Notice::add($this->project, $user, "В проекте {$this->project->name} добавлено мероприятие");
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Код',
            'project_id' => 'Проект',
            'name' => 'Наименование',
            'date_plan' => 'Дата завершения',
            'is_complete' => 'Завершена',
        ];
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

    /**
     * Gets query for [[Publications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublications()
    {
        return $this->hasMany(Publication::class, ['event_id' => 'id']);
    }

    public static function getListByProject(Project $project): array {
        $models = self::find()->andWhere(['project_id' => $project->id])->orderBy('name')->all();
        $list = [];
        foreach($models as $model) {
            $list[$model->id] = $model->name;
        }
        return $list;
    }
}
