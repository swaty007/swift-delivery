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
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            /*
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            */
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
        Yii::$app->response->format = 'json';
        Yii::$app->controller->enableCsrfValidation = false;
        
        return parent::beforeAction($action);
    }

    public function actionIsZipAllowed(string $zip) {
        $response = [];
        $response['result'] = (boolean)Zipcode::find()->where(['zipcode' => (string)$zip])->count();
        return $this->sendResponse($response);
    }

    private function sendResponse($data = [], $code = 200) {
        Yii::$app->response->statusCode = 200;
        return $data;
    }
}
