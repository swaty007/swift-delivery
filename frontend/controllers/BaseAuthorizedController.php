<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;

abstract class BaseAuthorizedController extends Base18oldController
{
    public $allowedRole;

    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== $this->allowedRole) {
           return $this->redirect(Url::toRoute('/site/login'))->send();
        }

        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }
}
