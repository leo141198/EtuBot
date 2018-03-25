<?php

namespace app\components\Types;

use yii\base\Model;
use app\modules\Telegram\components\Types\From;
use app\modules\Telegram\components\Types\Chat;
use app\modules\Telegram\components\Types\Entities;
use app\modules\Telegram\components\Types\Document;

class Message extends Model {
    public $data = [];

    public $message_id;
    public $from;
    public $chat;
    public $date;
    public $text;
    public $entities;
    public $document;
    public $callback_query;

    public function rules()
    {
        return [
            [['message_id', 'from', 'chat', 'date', 'text', 'entities','callback_query'], 'safe'],
        ];
    }

    public function setAttributes($values, $safeOnly = true) {
        $this->setAttribute('From', $values);
        $this->setAttribute('Chat', $values);
        if(isset($values['entities'])) {
            $this->setAttribute('Entities', $values, 1);
        }
        if(isset($values['document'])) {
            $this->text = $values['caption'];
            $this->setAttribute('Document', $values, 1);
        }
        if(isset($values['photo'])) {
            $this->text = $values['caption'];
            $values['document'] = end($values['photo']);
            unset($values['photo']);
            $this->setAttribute('Document', $values);
            reset($values);
        }
        parent::setAttributes($values, $safeOnly);
    }

    /**
     * Устанавливает соответствующий класс-тип вместо декодированного JSON от телеграмма
     * @param $attr_name / название класс-типа с заглавной буквы
     * @param $values array исходнный массив
     * @param $index integer индекс в массиве, если нужен конкретрый элемент
     */
    public function setAttribute($attr_name, &$values, $index = null) {
        $attr_low = strtolower($attr_name);
        $class_name = $this->getClassName($attr_name);
        $this->$attr_low = new $class_name();
        \Yii::trace($this);
        if($index != null) {
            $this->$attr_low->setAttributes($values[$attr_low][$index-1]);
        } else {
            $this->$attr_low->setAttributes($values[$attr_low]);
        }
        \Yii::trace($this->$attr_low);
        unset($values[$attr_low]);
    }

    private function getClassName($class) {
        return "app\\modules\\Telegram\\components\\Types\\" . $class;
    }
}
