<?php

namespace app\components\Types;

use yii\base\Model;

class Entities extends Model {
    public $offset;
    public $length;
    public $type;

    public function rules()
    {
        return [
            [['offset', 'length', 'type'], 'safe'],
        ];
    }

}