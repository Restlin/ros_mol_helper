<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project_team".
 *
 * @property int $id
 * @property int $project_id ИД проекта
 * @property int $user_id ИД пользователя
 * @property int $type type
 * @property string|null $role Роль в проекте
 *
 * @property Project $project
 * @property User $user
 */
class ProjectTeam extends \yii\db\ActiveRecord
{
    const TYPE_MEMBER = 1;
    const TYPE_MENTOR = 2;
    const TYPE_INVESTOR = 3;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project_team';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'user_id'], 'required'],
            [['project_id', 'user_id', 'type'], 'default', 'value' => null],
            [['project_id', 'user_id', 'type'], 'integer'],
            [['type'], 'in', 'range' => [self::TYPE_MEMBER, self::TYPE_MENTOR]],
            [['role'], 'string', 'max' => 100],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::class, 'targetAttribute' => ['project_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function afterSave($insert, $changedAttributes) {
        if($insert && $this->type == self::TYPE_MEMBER) {
            Notice::add($this->project, $this->user, "Вас пригласили в проект {$this->project->name}!");
        }
        $user = Yii::$app->user->getIdentity()->user;
        $content = $insert ? "Добавлен участник {$this->user->fio} с ролью - {$this->role}" : "Изменен участник {$this->user->fio} с ролью - {$this->role}";
        Log::add($this->project, $user, $content);
        if($insert) {
            foreach($this->project->users as $user) {
                Notice::add($this->project, $user, "В проекте {$this->project->name} добавлен участник {$this->user->fio}");
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
            'user_id' => 'Пользователь',
            'type' => 'Тип',
            'role' => 'Роль в проекте',
        ];
    }

    public static function getTypeList(): array {
        return [
            self::TYPE_MEMBER => 'Участник',
            self::TYPE_MENTOR => 'Ментор',
            self::TYPE_INVESTOR => 'Инвестор',
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
