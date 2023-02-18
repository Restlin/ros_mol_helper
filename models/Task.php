<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property int $project_id Проект
 * @property string $name Наименование
 * @property int|null $user_id Исполнитель
 * @property string $date_plan Дата завершения
 * @property bool $is_complete Завершена
 *
 * @property Project $project
 * @property User $user
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'name', 'date_plan'], 'required'],
            [['project_id', 'user_id'], 'default', 'value' => null],
            [['project_id', 'user_id'], 'integer'],
            [['date_plan'], 'safe'],
            [['is_complete'], 'boolean'],
            [['name'], 'string', 'max' => 255],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::class, 'targetAttribute' => ['project_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function afterSave($insert, $changedAttributes) {
        $user = Yii::$app->user->getIdentity()->user;
        $content = $insert ? "Добавлена задача {$this->name} со сроком {$this->date_plan}" : "Изменена задача {$this->name} со сроком {$this->date_plan}";
        if($this->is_complete) {
            $content = "Завершена задача {$this->name} со сроком {$this->date_plan}";
        }
        Log::add($this->project, $user, $content);
        if($insert) {
            foreach($this->project->users as $user) {
                Notice::add($this->project, $user, "В проекте {$this->project->name} добавлена задача {$this->name}");
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
            'id' => 'ID',
            'project_id' => 'Проект',
            'name' => 'Наименование',
            'user_id' => 'Исполнитель',
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
