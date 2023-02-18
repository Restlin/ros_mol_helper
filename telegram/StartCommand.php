<?php


namespace app\telegram;

use app\models\User;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use yii\helpers\Html;
use yii\helpers\Url;
use Yii;

class StartCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'start';

    /**
     * @var string
     */
    protected $description = 'Start command';

    /**
     * @var string
     */
    protected $usage = '/start';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * @var bool
     */
    protected $private_only = false;

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $chatId = $this->getMessage()->getChat()->getId();        
        $user = User::findOne(['tg_id' => $chatId]);
        if ($user) {
            return $this->replyToChat("🙃 {$user->fio}, вы уже подписаны на уведомления.");
        }        

        $userId = $this->getMessage()->getText(true);
        return $this->replyToChat("проверка1 $userId");
        $user = $userId ? User::findOne(['id' => $userId]) : null;
        
        if ($user && !$user->tg_id) {
            $user->tg_id = $chatId;
            if ($user->save()) {
                $message = "Вы подписались на уведомления в Telegram.";
                return $this->replyToChat($message, [
                    'parse_mode' => 'HTML',
                ]);
            }
        }

        $options = [
            'disable_web_page_preview' => true,
            'parse_mode' => 'HTML',
            'reply_markup' => new InlineKeyboard([
                new InlineKeyboardButton([
                    'text' => 'Подписаться',
                    'url' => Url::to(['site/index']),
                ]),
            ]),
        ];
        
        $message = <<<HTML
ℹ <b>Для начала работы с ботом, выполните одно из действий.</b>

Для получения списка доступных команд, воспользуйтесь командой: <code>/help</code>
HTML;

        return $this->replyToChat($message, $options);
    }
}
