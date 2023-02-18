<?php

namespace app\models;

use app\models\ProjectResult;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $name Наименование
 * @property int $level Масштаб реализации
 * @property string|null $date_start Дата начала
 * @property string|null $date_end Дата окончания проекта
 * @property int|null $author_id Автор
 * @property int $status статус проекта
 *
 * @property User $author
 * @property ProjectResult $result
 * @property ProjectTeam[] $teams
 * @property User[] $users участники
 * @property Task[] $tasks
 * @property Event[] $events
 * @property Publication[] $publications
 */
class Project extends \yii\db\ActiveRecord
{
    const STATUS_DRAFT = 1;    
    const STATUS_CHECK = 2;
    const STATUS_GRANT = 3;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['level', 'author_id'], 'default', 'value' => null],
            [['status'], 'default', 'value' => self::STATUS_DRAFT],
            [['status'], 'in', 'range' => array_keys(self::getStatusList())],
            [['level', 'author_id', 'status'], 'integer'],
            [['date_start', 'date_end'], 'safe'],
            [['name'], 'string', 'max' => 200],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    public function afterSave($insert, $changedAttributes) {
        if($insert) {
            $team = new ProjectTeam();
            $team->user_id = $this->author_id;
            $team->type = ProjectTeam::TYPE_MEMBER;
            $team->project_id = $this->id;
            $team->role = 'Автор проекта';
            $team->save();
        }
        if(!$this->result) {
            $result = new ProjectResult();
            $result->project_id = $this->id;
            $result->save();
        }
        $user = Yii::$app->user->getIdentity()->user;
        $content = $insert ? "Создан новый проект: $this->name со сроками: {$this->date_start} - {$this->date_end}" : "Изменен проект: $this->name со сроками: {$this->date_start} - {$this->date_end}";
        Log::add($this, $user, $content);
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Код',
            'name' => 'Наименование',
            'level' => 'Масштаб реализации',
            'date_start' => 'Дата начала',
            'date_end' => 'Дата окончания',
            'author_id' => 'Автор',
            'status' => 'Статус',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[Result]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResult()
    {
        return $this->hasOne(ProjectResult::class, ['project_id' => 'id']);
    }

    /**
     * Gets query for [[ProjectTeams]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeams()
    {
        return $this->hasMany(ProjectTeam::class, ['project_id' => 'id']);
    }

    public function getUsers() {
        return $this->hasMany(User::class, ['id' => 'user_id'])->via('teams');
    }
    
    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks() {
        return $this->hasMany(Task::class, ['project_id' => 'id']);
    }

    /**
     * Gets query for [[Events]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvents() {
        return $this->hasMany(Event::class, ['project_id' => 'id']);
    }
    /**
     * Gets query for [[Publications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublications() {
        return $this->hasMany(Publication::class, ['event_id' => 'id'])->via('events');
    }

    public static function getLevelList(): array {
        return [
            1 => 'Муниципальный',
            2 => 'Региональный',
            3 => 'Межрегиональный',
            4 => 'Окружной',
            5 => 'Всероссийский',
            6 => 'Международный'
        ];
    }

    public static function getStatusList(): array {
        return [
            self::STATUS_DRAFT => 'Черновик',
            self::STATUS_CHECK => 'На проверке',
            self::STATUS_GRANT => 'Принят'
        ];
    }

    public function readyPercent(): float {
        $total = 0;
        $complete = 0;
        $types = [[$this], [$this->result], $this->teams, $this->tasks, $this->events, $this->publications];
        foreach($types as $models) {
            if(!$models) {
                $total += 10;
            }
            foreach($models as $model) {
                foreach($model->attributes as $attr) {
                    $total++;
                    if($attr !== null) {
                        $complete++;
                    }
                }
            }
        }
        return round($complete / $total, 2)*100;
    }
}
