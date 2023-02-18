<?php

namespace app\telegram;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class HelpCommand extends UserCommand
{
    protected $name = "help";
    protected $usage = "/help";
    protected $private_only = false;

    public function execute(): ServerResponse
    {
        $replyMessage = "
ℹ *Команды БОТа:*

`/start` - Подключение пользователя к боту
`/help` - Список доступных комманд";

        $options = [
            'disable_web_page_preview' => true,
            'parse_mode' => 'Markdown',
        ];

        return $this->replyToChat($replyMessage, $options);
    }
}
