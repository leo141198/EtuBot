<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "telegram_chat".
 *
 * @property integer $id
 * @property integer $chat_id
 * @property integer $user_id
 */
class TelegramChat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'telegram_chat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['chat_id'], 'required'],
            [['chat_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'chat_id' => 'Chat ID',
            'user_id' => 'User ID',
        ];
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function createChat($chat_id) {
        $chat = new self;
        $chat->chat_id = $chat_id;
        $chat->save();
        return $chat;
    }

    public static function chatExists($chat_id) {
        return self::find()->where(['chat_id' => $chat_id])->exists();
    }

    public static function chatUserSet($chat_id) {
        return isset(self::find()->where(['chat_id' => $chat_id])->one()->user_id);
    }

    public static function getChat($chat_id) {
        return self::find()->where(['chat_id' => $chat_id])->one();
    }
}
