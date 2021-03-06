<?php

namespace backend\controllers;


use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class BaseAdminController extends Controller
{
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::toRoute('/site/login'));
        }

        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }
}
