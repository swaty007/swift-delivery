<?php

namespace frontend\controllers;

use common\models\Supplier;
use common\models\User;
use frontend\models\SupplierForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class SupplierController extends BaseAuthorizedController
{
    public $allowedRole = User::USER_ROLE_SUPPLIER;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['confirm'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [];
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);

        if (!$this->isSupplierDataFilled() && Yii::$app->controller->action->id !== 'confirm') {
            return $this->redirect('/supplier/confirm')->send();
        }

        return true;
    }

    public function actionIndex() {
        $this->render('index');
    }

    public function actionConfirm() {
        $model = new SupplierForm();

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            return $this->redirect('/supplier/confirm-success');
        }

        return $this->render('confirm', ['model' => $model]);
    }
    public function actionConfirmSuccess() {

        return $this->render('confirm-success');
    }

    private function isSupplierDataFilled() {
        return (boolean)Supplier::find()->where(['supplier_id' => Yii::$app->user->getId()])->count();
    }
}
