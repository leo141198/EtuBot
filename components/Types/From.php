<?php

namespace app\components\Types;

use yii\base\Model;

class From extends Model {
    public $id;
    public $is_bot;
    public $first_name;
    public $last_name;
    public $language_code;

    public function rules()
    {
        return [
            [['id', 'is_bot', 'first_name', 'last_name', 'language_code'], 'safe'],
        ];
    }
}