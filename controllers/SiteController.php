<?php

namespace app\controllers;

use yii\web\Controller;

class SiteController extends Controller
{
    public function actionWebhook()
    {
        return $this->render('index');
    }

}
