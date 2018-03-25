<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "telegram_command".
 *
 * @property integer $id
 * @property string $name
 * @property string $function
 */
class TelegramCommand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'telegram_command';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'function'], 'required'],
            [['name', 'function'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'function' => 'Function',
        ];
    }
}
