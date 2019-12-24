<?php
namespace frontend\controllers;

use common\models\ProductOption;
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
    private $cart;
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
        Yii::$app->response->statusCode = $code;
        return $data;
    }

    public function actionAddToCart() {
        $option = (int)$this->postData['option'];
        $count = (int)$this->postData['count'];
        if ($count < 1) {
            return $this->sendResponse(['message' => 'Incorrect count'], 400);
        } else if (!ProductOption::findOne(['id' => $option])) {
            return $this->sendResponse(['message' => 'Incorrect product'], 400);
        }

        $cart = $this->getCart();

        if (!isset($cart[$option])) {
            $cart[$option] = (int)$count;
        } else {
            $cart[$option] += $count;
        }

        $this->saveCart($cart);
        return $this->sendResponse(['message' => 'ok']);
    }

    public function actionRemoveFromCart() {
        $option = (int)$this->postData['option'];
        $full = (boolean)Yii::$app->request->post('full', false);
        $cart = $this->getCart();

        if ($option == 0) {
            $this->saveCart([]);
            return $this->sendResponse(['message' => 'ok']);
        }

        if (!isset($cart[$option])) {
            return $this->sendResponse(['message' => 'This product not exits'], 400);
        }

        if ($full) {
            unset($cart[$option]);
        } else {
            if ($cart[$option] > 1) {
                $cart[$option] -= 1;
            } else {
                unset($cart[$option]);
            }
        }

        $this->saveCart($cart);
        return $this->sendResponse(['message' => 'ok']);
    }

    private function getCart() {
        if(!Yii::$app->session->isActive) {
            Yii::$app->session->open();
        }

        return json_decode(Yii::$app->session->get('cart', '{}'), true);
    }

    private function saveCart($cart) {
        if(!Yii::$app->session->isActive) {
            Yii::$app->session->open();
        }

        Yii::$app->session->set('cart', json_encode($cart));
    }
}
