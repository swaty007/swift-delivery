<?php

namespace frontend\controllers;

use common\models\Product;
use common\models\ProductOption;
use common\models\User;
use frontend\models\OrderForm;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\SupplierForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\bootstrap\ActiveForm;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class DevController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionOrder()
    {
        $model = new OrderForm();

        if ($model->load(Yii::$app->request->post())) {
            $model->supplier_id = null;//тут проверь
            $model->customer_id = Yii::$app->user->getId();

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            }

            if ($model->confirm()) {
//                return $this->redirect('/supplier/confirm-success');//тут проверь
            }
        }
        return $this->render('/customer/order', [
            'model' => $model,
            'gifts' => Product::getActiveList(),
            'cart' => $this->getCart()
        ]);
    }

    public function actionOnWay()
    {
        return $this->render('/customer/onWay');
    }

    public function actionSearching()
    {
        return $this->render('/customer/searching');
    }

    public function actionConfirm()
    {

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

        return $this->render('/supplier/confirm', [
            'model' => $model,
            'gifts' => Product::getActiveList(),
        ]);
    }


}
