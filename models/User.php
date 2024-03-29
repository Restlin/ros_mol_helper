<?php

namespace app\models;

use Yii;
use Longman\TelegramBot\Request;
use yii\helpers\Url;
use app\models\Event;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email Email
 * @property string $fio ФИО
 * @property int|null $tg_id TG id
 * @property string $password_hash Хеш пароля
 * @property int $role Роль
 * @property resource|null $photo Фото
 * @property string $about
 * @property string $url
 *
 * @property ProjectTeam[] $teams
 */
class User extends \yii\db\ActiveRecord
{
    /**
     *
     * @var \yii\web\UploadedFile
     */
    public $photoFile;
    public $password;
    
    const ROLE_DEFAULT = 1;
    const ROLE_INVESTOR = 2;
    const ROLE_MENTOR = 3;
    const ROLE_ADMIN = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    public function beforeValidate() {
        if($this->password) {
            $this->password_hash = \Yii::$app->security->generatePasswordHash($this->password);
        }
        if($this->photoFile) {
            $this->photo = file_get_contents($this->photoFile->tempName);
        }
        return parent::beforeValidate();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $roles = self::getRoleList();
        return [
            [['email', 'fio', 'password_hash'], 'required'],
            [['password'], 'required', 'on' => 'register'],
            [['tg_id', 'role'], 'default', 'value' => null],
            [['tg_id', 'role'], 'integer'],
            [['role'], 'in', 'range' => array_keys($roles)],
            [['photo'], 'safe'],
            [['photoFile'], 'file', 'extensions' => 'png, jpg'],
            [['email', 'password_hash', 'url'], 'string', 'max' => 100],
            [['fio'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 30],
            [['about'], 'string', 'max' => 8000],
            [['email'], 'unique'],
            [['tg_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'fio' => 'ФИО',
            'tg_id' => 'TG id',
            'password_hash' => 'Хеш пароля',
            'password' => 'Пароль',
            'role' => 'Роль',
            'photo' => 'Фото',
            'about' => 'Описание',
            'url' => 'Ссылка на резюме',
        ];
    }

    /**
     * Gets query for [[ProjectTeam]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeams()
    {
        return $this->hasMany(ProjectTeam::class, ['user_id' => 'id']);
    }

    public static function getRoleList(): array {
        return [
            self::ROLE_DEFAULT => 'Участник',
            self::ROLE_ADMIN => 'Администратор',
            self::ROLE_MENTOR => 'Наставник',
            self::ROLE_INVESTOR => 'Инвестор',
        ];
    }

    public static function createAdmin(string $email, string $password) {
        $user = new User();
        $user->email = $email;
        $user->fio = 'Админ';
        $user->password = $password;
        $user->role = self::ROLE_ADMIN;
        $user->save();
        return $user;
    }

    public static function getList(): array {
        $models = self::find()->orderBy('fio')->all();
        $list = [];
        foreach($models as $model) {
            $list[$model->id] = $model->fio;
        }
        return $list;
    }

    public static function getListByProject(Project $project): array {
        $models = self::find()->innerJoinWith('teams t', false)->andWhere(['t.project_id' => $project->id])->orderBy('fio')->all();
        $list = [];
        foreach($models as $model) {
            $list[$model->id] = $model->fio;
        }
        return $list;
    }

    public function parseCommand(string $text) {
        $content = "Принята команда: $text\n";
        $words = explode(' ', $text);
        if(preg_match('/создать|редактировать/ui', $words[0]) !== false) {
            $content .= "Вы хотите редактировать проект\n";
            $project = Project::find()->joinWith(['teams t'], false)->andWhere(['t.user_id' => $this->id])->orderBy('project_id desc')->limit(1)->one();
            if($project) {
                $url = Url::to(['project/view', 'id' => $project->id], true);
                $content .= "Ваш активный проект: {$project->name} $url\n";
            }

            if(preg_match('/мероприятие/ui', $words[1]) !== false) {
                $content .= "Вы хотите создать мероприятие!\n";
                //unset($words[1]);
                //unset($words[0]);
                //$name = implode($words);
                //$name = preg_replace("/^.+?мероприятие +/ui", $text);
                //$content .= "Вы хотите создать мероприятие $name!\n";
                /*$event = Event::add($project);
                if($event->id) {
                    $url = Url::to(['project/view', 'id' => $project->id, 'tab' => 'event'], true);
                    $content .= "Создано мероприятие: $url\n";
                }*/
            }
        }
        $telegram = Yii::$container->get(\Longman\TelegramBot\Telegram::class);
        Request::sendMessage(['chat_id' => $this->tg_id, 'text' => $content]);
    }

    public function getGameTitles(): string {
        $mages = Project::find()->andWhere(['author_id' => $this->id])->count();
        $fighters = Task::find()->andWhere(['user_id' => $this->id, 'is_complete' => true])->count();
        $bards = Log::find()->andWhere(['user_id' => $this->id])->andWhere("content ~* 'мероприятие'")->count();
        $archers = ProjectTeam::find()->andWhere(['user_id' => $this->id])->count();
        $result = [];
        if($mages) {
            $result[] = "&#129497; Маг $mages-ого уровня (за созидание своих проектов)";
        }
        if($archers) {
            $result[] = "&#127993; Лучник $archers-ого уровня (за участие в разных проектах)";
        }
        if($bards) {
            $result[] = "&#129685; Бард $bards-ого уровня (за организацию мероприятий)";
        }
        if($fighters) {
            $result[] = "&#9876; Воин $fighters-ого уровня (за завершение задач)";
        }
        return implode("<br>", $result);
    }

}
