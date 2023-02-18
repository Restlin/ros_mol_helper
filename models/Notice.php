<?php

namespace app\models;

use Longman\TelegramBot\Request;
use Yii;

/**
 * This is the model class for table "notice".
 *
 * @property int $id
 * @property int $project_id Проект
 * @property int $user_id ИД пользователя
 * @property string $date_in Время сообщения
 * @property string $content Содержание
 * @property bool $is_viewed Просмотрено
 *
 * @property Project $project
 * @property User $user
 */
class Notice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notice';
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
            [['is_viewed'], 'boolean'],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::class, 'targetAttribute' => ['project_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function afterSave($insert, $changedAttributes) {
        if($this->user->tg_id) {
            $telegram = Yii::$container->get(\Longman\TelegramBot\Telegram::class);
            Request::sendMessage(['chat_id' => $this->tg_id, 'text' => $this->content]);
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
            'user_id' => 'ИД пользователя',
            'date_in' => 'Время сообщения',
            'content' => 'Содержание',
            'is_viewed' => 'Просмотрено',
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

    public static function add(Project $project, User $user, $content) {
        $model = new Notice();
        $model->project_id = $project->id;
        $model->user_id = $user->id;
        $model->date_in = date('d.m.Y H:i:s');
        $model->content = $content;
        $model->is_viewed = false;
        $model->save();
    }

    public static function actualCount(User $user) {
        return self::find()->andWhere(['user_id' => $user->id, 'is_viewed' => false])->count();
    }
}
