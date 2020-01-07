<?php
namespace frontend\controllers;

use common\models\Order;
use common\models\Product;
use common\models\ProductOption;
use common\models\User;
use frontend\models\OrderForm;
use frontend\models\RatingForm;
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

/**
 * Site controller
 */
class SiteController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action)
    {
        $allowedPages = [
            'index', 'logout', 'order', 'order-status'
        ];

        if(!Yii::$app->user->isGuest && !in_array(Yii::$app->controller->action->id, $allowedPages)) {
            switch (Yii::$app->user->identity->role) {
                case User::USER_ROLE_SUPPLIER:
                    $redirectUrl = '/supplier/index';
                    break;
                default:
                    $redirectUrl = null;
                    break;

            }

            if(!is_null($redirectUrl)) {

                return $this->redirect($redirectUrl)->send();
            }
        }

        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $this->layout = 'access';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect('/site/redirect-after-login');
        } else {
            $model->password = '';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionRedirectAfterLogin() {
        return $this->redirect('/');
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionConfirmSuccess() {

        return $this->render('../supplier/confirm-success');
    }
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionOrder() {
        $model = new OrderForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            }

            if ($model->createOrder()) {
                return $this->redirect('/site/order-status/?l=' . $model->instance->weblink);
            }
        }
        return $this->render('/customer/order', ['model' => $model, 'gifts' => Product::getActiveList(), 'cart' => $this->getCart()]);
    }

    public function actionOrderStatus($l) {
        if (!($order = Order::find()->where(['weblink' => $l])->with('orderItems')->with('supplier')->one())) {
            return $this->redirect('/site/index');
        }

//        foreach ($order->getOrderItems() as $item) {
//
//        }
        $model = new RatingForm();
        return $this->render('/customer/order-status', ['order' => $order, 'model' => $model]);
    }
    public function actionCancelOrder($l) {

    }
    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $this->layout = 'access';
        $model = new SignupForm();
        $model->role = 3;

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            return $this->redirect('/supplier/index');
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionSignupComplete()
    {
        $model = new SupplierForm();

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('/supplier/confirm', [
            'model' => $model,
            'gifts' => Product::getActiveList(),
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = 'access';
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        $this->layout = "login";
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $this->layout = "login";
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    // TODO: Implement Session model and remove it there
    static public function getCart() {
        if(!Yii::$app->session->isActive) {
            Yii::$app->session->open();
        }

        $rawCart = json_decode(Yii::$app->session->get('cart', '{}'), true);
        $products = ProductOption::find()
            ->with('product')
            ->where(['IN', 'id', array_keys($rawCart)])
            ->orderBy('price')
            ->asArray()
            ->all();

        $cart = [];

        foreach ($products as $key => $product) {
            $cart[$key] = $product;
            $cart[$key]['count'] = $rawCart[(int)$product['id']];
        }

        return $cart;
    }
}
