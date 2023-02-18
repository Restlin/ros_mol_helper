<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property int $project_id Проект
 * @property int $user_id Пользователь
 * @property string $date_in Время
 * @property string $content Изменение
 *
 * @property Project $project
 * @property User $user
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'user_id', 'date_in', 'content'], 'required'],
            [['project_id', 'user_id'], 'default', 'value' => null],
            [['project_id', 'user_id'], 'integer'],
            [['date_in'], 'safe'],
            [['content'], 'string'],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::class, 'targetAttribute' => ['project_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Проект',
            'user_id' => 'Пользователь',
            'date_in' => 'Время',
            'content' => 'Изменение',
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

    public static function add(Project $project, User $user, string $content): Log {
        $log = new Log();
        $log->project_id = $project->id;
        $log->user_id = $user->id;
        $log->date_in = date('d.m.Y H:i:s');
        $log->content = $content;
        $log->save();
        return $log;
    }
}
