<?php
namespace frontend\controllers;

use common\models\Zipcode;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\SupplierForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
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

/**
 * Site controller
 */
class ApiController extends Controller
{
    private $postData;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
        ];
    }

    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect('/')->send();
        }

        Yii::$app->response->format = 'json';
        Yii::$app->controller->enableCsrfValidation = false;
        $this->postData = Yii::$app->request->post();
        
        return parent::beforeAction($action);
    }

    public function actionIsZipAllowed() {
        if (!Yii::$app->request->isPost) {
            return $this->sendResponse(null, 405);
        }

        $response = ['result' => (boolean)Zipcode::find()
            ->where(['zipcode' => @(string)$this->postData['zip']])
            ->count()];

        return $this->sendResponse($response);
    }

    private function sendResponse($data = [], $code = 200) {
        Yii::$app->response->statusCode = 200;
        return $data;
    }
}
