<?php

namespace app\components;

use app\components\Types\Message;
use yii\helpers\VarDumper;
use Yii;

class Parser {
    const MESSAGE_SIGN_FOR_LAB = 'Записаться на сдачу лабы';

    public $message;
    public $chat;

    public function __construct($message) {
        $this->message = new Message();
        $this->message->setAttributes($message);
        if ($this->isPrivate()) {
            if (!TelegramChat::chatExists($this->message->chat->id)) {
                $this->chat = TelegramChat::createChat($this->message->chat->id);
            } else {
                $this->chat = TelegramChat::getChat($this->message->chat->id);
            }
        }
    }

    public function parse() {
        //если сообщение из приватного чата
        if ($this->isPrivate() && $this->isAuthenticated()) {
            //если в сообщении есть команда, то парсим ее
            if (isset($this->message->entities) && $this->message->entities->type == 'bot_command') {
                return $this->parseCommand();
            } else {
                if (!$this->parseTextCommand() && !$this->parseByLastContent()) {
                    $buttons = [[self::MESSAGE_SIGN_FOR_LAB]];
                    $keyboard = Yii::$app->telegram->buildKeyBoard($buttons);
                    $content = [
                        'chat_id' => $this->message->chat->id,
                        'text' => 'Неизвестная команда',
                        'reply_markup' => $keyboard,
                    ];
                    Yii::$app->telegram->sendMessage($content);
                    //тут будет парсер контекста
                }
            }
        }
    }

    public function parseCommand() {
        $command = $this->getCommand();
        $this->executeCommand($command);
        return true;
    }

    public function parseTextCommand() {
        //тут будет нормальный парсер, но пока и так сойдет
        if ($this->message->text == self::MESSAGE_SIGN_FOR_LAB) {
            $this->executeCommand('signForLab');
            return true;
        }
        return false;
    }

    public function parseByLastContent() {
        $last_content = TelegramContentMessage::findLast($this->chat->id);
        if ($last_content == null || $last_content->contentType->next == null) {
            return false;
        }
        $this->executeCommand($last_content->contentType->next->action->name,
            $last_content->contentType->next->command->function);
        return true;
    }

    public function executeCommand($class, $command = null) {
        $class_name = $this->getClass($class);
        if ($class == 'start') {
            $action = new $class_name(true, $this->message);
        } else {
            $action = new $class_name(true, $this->message, $this->chat);
        }
        if ($command == null) {
            $action->$class();
        } else {
            $action->$command();
        }
    }

    public function isPrivate() {
        return isset($this->message->chat) && $this->message->chat->type == 'private';
    }

    public function isAuthenticated() {
        if (TelegramChat::chatExists($this->message->chat->id) && TelegramChat::chatUserSet($this->message->chat->id)) {
            return true;
        }
        return false;
    }

    private function getCommand() {
        return substr($this->message->text, $this->message->entities->offset + 1,
            $this->message->entities->length);
    }

    private function getClass($command) {
        return "app\\components\\Actions\\" . ucfirst($command);
    }

}