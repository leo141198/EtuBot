<?php

namespace app\models;

use app\components\TelegramComponent;
use Yii;

/**
 * This is the model class for table "telegram_content_type".
 *
 * @property integer $id
 * @property integer $action_id
 * @property string $command_id
 * @property string $next_id
 * @property bool $accept_file
 */
class TelegramContentType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'telegram_content_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['action_id', 'command_id'], 'required'],
            [['action_id', 'command_id', 'next_id'], 'integer'],
            [['accept_file'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action_id' => 'Action ID',
            'command_id' => 'command_id',
            'next_id' => 'next_id',
            'accept_file' => 'Accept File',
        ];
    }

    public function getAction() {
        return $this->hasOne(TelegramAction::className(), ['id' => 'action_id']);
    }

    public function getCommand() {
        return $this->hasOne(TelegramCommand::className(), ['id' => 'command_id']);
    }

    public function getNext() {
        return $this->hasOne(self::className(), ['command_id' => 'next_id']);
    }

    public static function findByContentType($type) {
        $command = TelegramCommand::find()->where(['name' => $type])->one();
        return self::find()->where(['command_id' => $command->id])->one();
    }
}
