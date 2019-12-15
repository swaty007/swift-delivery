<?php

namespace frontend\controllers;

use common\models\Product;
use common\models\Supplier;
use common\models\User;
use frontend\models\SupplierForm;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

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
        $actionId = Yii::$app->controller->action->id;

        if (!$this->isSupplierDataFilled() && !in_array($actionId, ['confirm'])) {
            return $this->redirect('/supplier/confirm')->send();
        }

        return true;
    }

    public function actionIndex() {
        return $this->render('confirm-success');
    }

    public function actionConfirm() {
        if($this->isSupplierDataFilled()) {
            return $this->redirect('confirm-success');
        }

        $model = new SupplierForm();

        if ($model->load(Yii::$app->request->post())) {
            $model->logo = UploadedFile::getInstance($model, 'logo');
            $model->product_image = UploadedFile::getInstance($model, 'product_image');
            $model->supplier_id = Yii::$app->user->getId();

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            }

            if ($model->signup()) {
                return $this->redirect('/supplier/confirm-success');
            }
        }
        return $this->render('confirm', ['model' => $model, 'gifts' => Product::getActiveList()]);
    }

    public function actionConfirmSuccess() {
        return $this->render('confirm-success');
    }

    private function isSupplierDataFilled() {
        return (boolean)Supplier::find()->where(['supplier_id' => Yii::$app->user->getId()])->count();
    }
}
