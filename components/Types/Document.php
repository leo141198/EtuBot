<?php

namespace app\components\Types;

use yii\base\Model;
use Yii;
use yii\base\Security;

class Document extends Model {
    public $file_name;
    public $mime_type;
    public $file_id;
    public $file_size;

    public function rules() {
        return [
            [['file_name', 'mime_type', 'file_id', 'file_size'], 'safe'],
        ];
    }

    public static function downloadFile($document) {
        $result = Yii::$app->telegram->getFile($document->file_id);
        $folder = Yii::getAlias('@app/web/files/uploads/') . Yii::$app->getSecurity()->generateRandomString();
        if (!isset($document->file_name)) {
            $document->file_name = "photo_" . Yii::$app->getSecurity()->generateRandomString(10) . ".jpg";
        }
        mkdir($folder);
        Yii::$app->telegram->downloadFile($result['result']['file_path'], $folder . '/' . $document->file_name);
    }
}