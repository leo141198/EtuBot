<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "telegram_content_message".
 *
 * @property integer $id
 * @property integer $chat_id
 * @property string $text
 * @property integer $action_id
 * @property integer $content_type_id
 * @property string $created
 */
class TelegramContentMessage extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'telegram_content_message';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['chat_id', 'text', 'action_id', 'content_type_id'], 'required'],
            [['chat_id', 'action_id', 'content_type_id'], 'integer'],
            [['text'], 'string'],
            [['created'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'chat_id' => 'Chat ID',
            'text' => 'Text',
            'action_id' => 'Action ID',
            'content_type_id' => 'Content Type',
            'created' => 'Created',
        ];
    }

    public function getAction() {
        return $this->hasOne(TelegramAction::className(), ['id' => 'action_id']);
    }

    public function getContentType() {
        return $this->hasOne(TelegramContentType::className(), ['id' => 'content_type_id']);
    }

    public static function findLast($chat_id) {
        return self::find()->where(['chat_id' => $chat_id])->orderBy(['created' => SORT_DESC, 'id' => SORT_DESC])->one();
    }

    public static function createContentMessage($chat_id, $text, $action_id, $type_id) {
        $model = new self;
        $model->chat_id = $chat_id;
        $model->text = $text;
        $model->action_id = $action_id;
        $model->content_type_id = $type_id;
        $model->save();
    }

    public static function findLastByContentType($chat_id, $content_type) {
        return self::find()->where(['chat_id' => $chat_id])
            ->andWhere(['content_type_id' => TelegramContentType::findByContentType($content_type)->id])->orderBy(['id' => SORT_DESC])->one();
    }
}
