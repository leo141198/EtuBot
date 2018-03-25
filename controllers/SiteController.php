<?php

namespace app\controllers;

use app\components\Parser;
use yii\web\Controller;
use Yii;

class SiteController extends Controller
{
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    public function actionWebhook()
    {
        $result = \Yii::$app->telegram->getData();
        Yii::trace($result);
        if (isset($result['message'])) {
            $parser = new Parser($result['message']);
            $parser->parse();
        }
    }

}
