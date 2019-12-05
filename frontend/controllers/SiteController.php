<?php
namespace frontend\controllers;

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
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
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
    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionWebPush()
    {

        $notifications = [
            [
                'subscription' => Subscription::create([
                    'endpoint' => 'https://fcm.googleapis.com/fcm/send/ewouRpP09SU:APA91bFY82LvrQuQ1TNlCzL9VqOhR4rBQ1ex8jfCuKrZ9DtHtugn51tYjLyTUsrNoxYSGYxE0L3Wu6OPkZQzGV1XEr5Lo6gSNSPY8nM3hnjTIMDk0MxjvM9gJ7EdKzx7sWcdWlzckXGJ', // Firefox 43+,
                    'publicKey' => 'BLAlJkwiyef6Z9FEpOhsTMTZ2dIpDSkL35baoHnijsMttGhENQYOKRl4WbLAuU_2Pg0D1OPWdxzObdbQfQoiv-M', // base 64 encoded, should be 88 chars
                    'authToken' => 'SvmxLDiq7gV4lQjX_InxjQ', // base 64 encoded, should be 24 chars
                ]),
                'payload' => 'hello !',
            ], [
                'subscription' => Subscription::create([
                    'endpoint' => 'https://fcm.googleapis.com/fcm/send/ewouRpP09SU:APA91bFY82LvrQuQ1TNlCzL9VqOhR4rBQ1ex8jfCuKrZ9DtHtugn51tYjLyTUsrNoxYSGYxE0L3Wu6OPkZQzGV1XEr5Lo6gSNSPY8nM3hnjTIMDk0MxjvM9gJ7EdKzx7sWcdWlzckXGJ', // Chrome
                ]),
                'payload' => null,
            ], [
                'subscription' => Subscription::create([
                    'endpoint' => 'https://fcm.googleapis.com/fcm/send/ewouRpP09SU:APA91bFY82LvrQuQ1TNlCzL9VqOhR4rBQ1ex8jfCuKrZ9DtHtugn51tYjLyTUsrNoxYSGYxE0L3Wu6OPkZQzGV1XEr5Lo6gSNSPY8nM3hnjTIMDk0MxjvM9gJ7EdKzx7sWcdWlzckXGJ',
                    'publicKey' => 'BLAlJkwiyef6Z9FEpOhsTMTZ2dIpDSkL35baoHnijsMttGhENQYOKRl4WbLAuU_2Pg0D1OPWdxzObdbQfQoiv-M',
                    'authToken' => 'SvmxLDiq7gV4lQjX_InxjQ',
                    'contentEncoding' => 'aesgcm', // one of PushManager.supportedContentEncodings
                ]),
                'payload' => '{msg:"test"}',
            ], [
                'subscription' => Subscription::create([ // this is the structure for the working draft from october 2018 (https://www.w3.org/TR/2018/WD-push-api-20181026/)
                    "endpoint" => "https://fcm.googleapis.com/fcm/send/ewouRpP09SU:APA91bFY82LvrQuQ1TNlCzL9VqOhR4rBQ1ex8jfCuKrZ9DtHtugn51tYjLyTUsrNoxYSGYxE0L3Wu6OPkZQzGV1XEr5Lo6gSNSPY8nM3hnjTIMDk0MxjvM9gJ7EdKzx7sWcdWlzckXGJ",
                    "keys" => [
                        'p256dh' => 'BG79EW1-T-iJEUG_r45v5rGqY_GVi9B0_QYUqc11y9rh-0eCApbEg3swvjWOpbAFNbHu5UTMmM4IGTbSWEsfPGA',
                        'auth' => 'SvmxLDiq7gV4lQjX_InxjQ'
                    ],
                ]),
                'payload' => '{"msg":"Hello World!"}',
            ],
        ];
        $webPush = new WebPush();

        foreach ($notifications as $notification) {
            $status = $webPush->sendNotification(
                $notification['subscription'],
                $notification['payload'] // optional (defaults null)
            );
            echo var_dump($status);
        }
        /**
         * send one notification and flush directly
         * @var \Generator<MessageSentReport> $sent
         */
        $sent = $webPush->sendNotification(
            $notifications[0]['subscription'],
            $notifications[0]['payload'], // optional (defaults null)
            true // optional (defaults false)
        );
        echo var_dump($sent);die();
        return;

        $url = 'https://fcm.googleapis.com/fcm/send/fyNGiGMWk5E:APA91bG0f5Iu_b5tbhPvGqGXXaLz1mfOSINCDn_Un3CyKfZ7Tf8S8cY5gArKVN2wFKSI2t6B6bn6QDCnBStVXlTjd3b5_-D3xSPEQ6ni05yV1emdpUf_76n7bJ06o6H-GzSs_ny6H8dk';
        $YOUR_API_KEY = 'AAAAC1fvc0U:APA91bElxlcBu-qHwQYLWhWPdnWFWm_qw49w_xQ-r9tsz5h-cNbakwjUu8zl-BWK6VP8R18t1sFCWOas3rrVLY-aqyW777ukw13uRg0rSCczIe_NiRA95PETwhA7Esht3X5KmxfEe40D'; // Server key
        $YOUR_TOKEN_ID = 'A8_wC7OYpd3ojpJGEy82Yw'; // Client token id

        $request_body = [
            'to' => $YOUR_TOKEN_ID,
            'notification' => [
                'title' => 'Ералаш',
                'body' => sprintf('Начало в %s.', date('H:i')),
                'icon' => 'https://eralash.ru.rsz.io/sites/all/themes/eralash_v5/logo.png?width=192&height=192',
                'click_action' => 'http://eralash.ru/',
            ],
        ];
        $fields = json_encode($request_body);

        $request_headers = [
            'Content-Type: application/json',
            'Authorization: key=' . $YOUR_API_KEY,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        curl_close($ch);

        echo $response;
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

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
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

        return $this->render('../supplier/create', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = "login";
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
}
