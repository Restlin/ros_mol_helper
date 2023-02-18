<?php

namespace app\controllers;

use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Yii;
use app\telegram\StartCommand;
use app\telegram\HelpCommand;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;

/**
 * DepController implements the CRUD actions for Dep model.
 */
class TgController extends Controller
{
    private $telegram;
    private ?User $user;

    public function __construct($id, $module, Telegram $telegram, $config = []) {
        $this->user = Yii::$app->user->isGuest ? null : Yii::$app->user->getIdentity()->user;
        $this->telegram = $telegram;
        parent::__construct($id, $module, $config);
    }
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                /*'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['connect', 'test'],
                            'roles' => ['@'],                            
                        ],
                        [
                            'allow' => true,
                            'actions' => ['hook'],
                            'roles' => ['?'],
                        ],
                    ],
                ],*/
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action) {
        if ($action->id == 'hook') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionConnect() {
        if($this->user->tg_id) {
            throw new BadRequestHttpException('У вас уже подключен телеграм!');
        }
        return $this->redirect("https://telegram.me/itAnimalsRosMolbot?start={$this->user->id}");
    }

    public function actionTest() {
        if($this->user->tg_id) {
            Request::sendMessage(['chat_id' => $this->user->tg_id, 'text' => "Тестовое сообщение"]);
        }
        $this->redirect(['/user/my']);
    }

    public function actionHook()
    {
        try {
            $this->telegram->enableAdmins([166851699]);
            $this->telegram->addCommandClasses([
                StartCommand::class,
                HelpCommand::class,
            ]);
            $this->telegram->handle();                        

            $input = Request::getInput();
            $obj = json_decode($input, true);
            $chatId = $obj['message']['from']['id'];
            $text = isset($obj['message']['text']) ? $obj['message']['text'] : null;

            $user = User::findOne(['tg_id' => $chatId]);
            if($user) {
                $user->parseCommand($text);
            }
            //debug
            /*Request::sendMessage([
                'chat_id' => 166851699,
                'text' => $text.$input,
            ]);*/



        } catch (TelegramException $e) {
            Yii::error($e->getMessage());
        }
        $this->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'status' => 200,
            'message' => 'OK',
        ];
    }
}
