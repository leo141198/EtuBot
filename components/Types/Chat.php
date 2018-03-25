<?php

namespace app\components\Types;

use yii\base\Model;

class Chat extends Model {
    public $id;
    public $first_name;
    public $last_name;
    public $type;

    public function rules()
    {
        return [
            [['id', 'first_name', 'last_name', 'type'], 'safe'],
        ];
    }
}