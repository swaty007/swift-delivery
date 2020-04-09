<?php

namespace frontend\controllers;

use common\models\GoogleMaps;
use common\models\Log;
use common\models\Message;
use common\models\Order;
use common\models\OrderItem;
use common\models\OrderQuery;
use common\models\Product;
use common\models\ProductOption;
use common\models\Rating;
use common\models\Supplier;
use common\models\SupplierItemRelation;
use common\models\Twilio;
use common\models\User;
use frontend\models\SupplierEditForm;
use frontend\models\SupplierForm;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;

class SupplierController extends BaseAuthorizedController
{
    public $allowedRole = User::USER_ROLE_SUPPLIER;

    /**
     * @var Supplier|null
     */
    public $supplierModel;

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
        if ($action->id == 'show-order' && Yii::$app->user->isGuest) {
            $link = Yii::$app->request->get('l');
            $session = Yii::$app->session;

            if (!$session->isActive) {
                $session->open();
            }

            $session->set('orderLink', $link);
        }

        parent::beforeAction($action);
        $actionId = Yii::$app->controller->action->id;

        if ($this->isSupplierDataFilled() == false && $actionId != 'confirm') {
            return $this->redirect('confirm')->send();
        } elseif ($actionId != 'confirm') {
            $this->supplierModel = $this->getSupplierModel();
            Yii::$app->view->params['supplierModel'] = $this->supplierModel;

            if (!$this->supplierModel->is_active && !in_array($actionId, ['need-activation', 'confirm'])) {
                return $this->redirect('need-activation')->send();
            }
        }

        return true;
    }

    public function actionNeedActivation()
    {
        if ($this->supplierModel->is_active) {
            return $this->redirect('index')->send();
        }

        return $this->render('need-activation');
    }

    public function actionIndex($cancelSupplier = 0, $cancelDeliver = 0, $complete = 0)
    {
        if ($cancelSupplier) {
            Yii::$app->session->setFlash('success', 'ORDER_STATUS_CANCELLED_BY_SUPPLIER');
            $this->cancelOrder($cancelSupplier, Order::ORDER_STATUS_CANCELLED_BY_SUPPLIER);
        }

        if ($cancelDeliver) {
            Yii::$app->session->setFlash('success', 'ORDER_STATUS_CANCELLED_BY_DELIVER');
            $this->cancelOrder($cancelDeliver, Order::ORDER_STATUS_CANCELLED_BY_DELIVER);
        }

        if ($complete) {
            Yii::$app->session->setFlash('success', 'Delivery Completed');
            $this->complete($complete);
        }

        if ($this->supplierModel->isAllowedToTakeOrder()) {
            $queryData = OrderQuery::find()
                ->select('order_id')
                ->where(['and',
                    ['supplier_id' => $this->supplierModel->id],
                    ['order' => 0]
                ])
                ->asArray()
                ->all();

            $allowedIds = ArrayHelper::getColumn($queryData, 'order_id');

            $allowedToDeliver = Order::find()
                ->with('orderItems')
                ->with('customer')
                ->with('supplier')
                ->with('rating')
                ->where(['status' => Order::ORDER_STATUS_NEW])
                ->andWhere(['IN', 'order.id', $allowedIds])
                ->asArray()
                ->orderBy('id DESC')
                ->all();
        } else {
            $allowedToDeliver = [];
        }

        $inProgress = Order::find()
            ->with('orderItems')
            ->with('customer')
            ->with('supplier')
            ->with('rating')
            ->where(['IN', 'status',
                [
                    Order::ORDER_STATUS_IN_PROGRESS,
                    Order::ORDER_STATUS_DELIVER_NEAR_PLACE,
                    Order::ORDER_STATUS_DELIVER_AT_PLACE,
                ]])
            ->andWhere(['supplier_id' => $this->supplierModel->id])
            ->andWhere(['supplier_id'=> $this->supplierModel->id])
            ->asArray()
            ->orderBy('id DESC')
            ->all();

        $finished = Order::find()
            ->with('orderItems')
            ->with('customer')
            ->with('supplier')
            ->with('rating')
            ->where(['IN', 'status',
                [
                    Order::ORDER_STATUS_COMPLETE,
                    Order::ORDER_STATUS_CANCELLED,
                    Order::ORDER_STATUS_CANCELLED_BY_CUSTOMER,
                    Order::ORDER_STATUS_CANCELLED_BY_DELIVER,
                    Order::ORDER_STATUS_CANCELLED_BY_SUPPLIER,
                ]])
            ->andWhere(['supplier_id' => $this->supplierModel->id])
            ->asArray()
            ->orderBy('id DESC')
            ->all();

        $rating = 0;

        if (Rating::find()->where(['supplier_id' => $this->supplierModel->id])->count()) {
            $ratingSum = Rating::find()->select('SUM(rating) as rating')->where(['supplier_id' => $this->supplierModel->id])->one()->rating;
            $ratingCount = Rating::find()->select('COUNT(rating) as rating')->where(['supplier_id' => $this->supplierModel->id])->one()->rating;
            $rating = $ratingSum / $ratingCount;
        }

        return $this->render('index', [
            'allowed' => $allowedToDeliver,
            'inProgress' => $inProgress,
            'finished' => $finished,
            'rating' => $rating,
            'ratingArray' => Rating::find()->where(['supplier_id' => $this->supplierModel->id])->all(),
            'earnings' => Order::find()->where(['supplier_id' => $this->supplierModel->id])->andWhere(['status' => Order::ORDER_STATUS_COMPLETE])->count(),
            'accepted' => Order::find()->where(['supplier_id' => $this->supplierModel->id])->count(),
            'mounthlyEarnings' => Order::find()
                ->select('SUM(total) as total')
                ->where(['supplier_id' => $this->supplierModel->id])
                ->andWhere(['status' => Order::ORDER_STATUS_COMPLETE])
                ->andWhere(['>', 'created_at', date('Y-m-d H:i:s', strtotime('today - 30 days'))])
                ->one()->total
        ]);
    }

    function getNeedleArray($source, $keys = [])
    {
        $result = [];

        foreach ($keys as $key) {
            if (is_array($source[$key]) && count($source[$key])) {
                $result[$key] = $source[$key][0];
            }
        }

        return $result;
    }

    public function actionConfirm()
    {
        if ($this->isSupplierDataFilled()) {
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

    public function actionEditProfile()
    {
        $model = new SupplierEditForm();
        $model->supplier_id = $this->supplierModel->id;
        $model->name = $this->supplierModel->name;
        $model->zip = $this->supplierModel->zip;
        $model->address = $this->supplierModel->address;
        $model->address_2 = $this->supplierModel->address_2;
        $model->web_url = $this->supplierModel->website;
        $model->product_name = $this->supplierModel->product_name;

        if ($model->load(Yii::$app->request->post())) {
            $product_image = UploadedFile::getInstance($model, 'product_image');
            $logo = UploadedFile::getInstance($model, 'logo');
            $model->logo = $logo;
            $model->product_image = $product_image;

            $model->supplier_id = Yii::$app->user->getId();

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            }

            if ($model->edit()) {
                return $this->redirect('/supplier/index');
            }
        }

        $rating = 0;

        if (Rating::find()->where(['supplier_id' => $this->supplierModel->id])->count()) {
            $ratingSum = Rating::find()->select('SUM(rating) as rating')->where(['supplier_id' => $this->supplierModel->id])->one()->rating;
            $ratingCount = Rating::find()->select('COUNT(rating) as rating')->where(['supplier_id' => $this->supplierModel->id])->one()->rating;
            $rating = $ratingSum / $ratingCount;
        }

        return $this->render('edit', [
            'model' => $model,
            'gifts' => Product::getActiveList(),
            'supplier' => $this->supplierModel,
            'selectedGifts' => ArrayHelper::index(SupplierItemRelation::find()->where(['supplier_id' => $this->supplierModel->supplier_id])->asArray()->all(), 'item_id'),
            'rating' => $rating,
        ]);
    }

    public function actionShowOrder($l) {
        $order = Order::find()
            ->with('orderItems')
            ->with('customer')
            ->with('supplier')
            ->with('rating')
            ->where(['AND',
                ['order.weblink' => $l],
                ['OR',
                    ['order.supplier_id' => null],
                    ['order.supplier_id' => $this->supplierModel->id],
                ]])
            ->one();

        $timeToTake = 0;
        if(is_null($order->supplier_id)) {
            $oq = OrderQuery::findOne(['order_id' => $order->id, 'supplier_id' => $this->supplierModel->id, 'order' => 0]);

            if(!($oq)) {
                Yii::$app->session->setFlash('success', 'Order access');
                return $this->redirect('/supplier/index');
            }

            $timeToTake = $oq->time_start_query + Yii::$app->params['timeToTakeOrder'] - time();
        }

        if ($order) {
            return $this->render('show-order', ['order' => $order, 'timeToTake' => ($timeToTake >= 0) ? $timeToTake : 0]);
        } else {
            Yii::$app->session->setFlash('success', 'Order access');
            return $this->redirect('/supplier/index');
        }
    }

    public function actionConfirmSuccess()
    {
        return $this->render('confirm-success');
    }

    private function isSupplierDataFilled()
    {
        return (boolean)Supplier::find()->where(['supplier_id' => Yii::$app->user->getId()])->count();
    }

    private function getSupplierModel()
    {
        return Supplier::findOne(['supplier_id' => Yii::$app->user->getId()]);
    }

    private function takeOrder()
    {
        if (!($order = Order::findOne(['id' => Yii::$app->request->post('id')]))) {
            return false;
        }

        if ($order->status !== Order::ORDER_STATUS_NEW) {
            return false;
        }

        $supplier = $this->getSupplierModel();

        $duration = Yii::$app->request->post('deliveryTime') . ' mins';
        $order->deliver_name = Yii::$app->request->post('deliverName');
        $order->delivery_duration = date('h:iA', strtotime($duration)) . ' | ' . $duration;
        $order->supplier_id = $supplier->id;
        $order->status = Order::ORDER_STATUS_IN_PROGRESS;
        $number = User::find()->where(['id' => $order->customer_id])->one()->phone_number;
        $products = [];
        $rawProducts = [];

        foreach (OrderItem::findAll(['order_id' => $order->id]) as $orderItem) {
            $productName = Product::find()->where(['id' => $orderItem->product_item_id])->one()->name;
            if (!isset($rawProducts[$productName])) {
                $rawProducts[$productName] = $orderItem->count;
            } else {
                $rawProducts[$productName] += $orderItem->count;
            }
        }

        foreach ($rawProducts as $name => $count) {
            $products[] = $count . ' ' . $name;
        }

        $messageCustomer = "Your order for " . implode(' & ', $products) . " ($" . $order->total . ") is on the way! " . $order->deliver_name . ", from " . $this->supplierModel->name . ", should arrive in about $order->delivery_duration";

        Twilio::sendSms($number, $messageCustomer);

        OrderQuery::deleteAll(['order_id' => $order->id]);
        $order->save();
        return $order;
    }

    public function actionCalculateDelivery($id)
    {
        Yii::$app->response->format = 'json';

        if (!($order = Order::findOne($id))) {
            return false;
        }

        if ($order->status !== Order::ORDER_STATUS_NEW) {
            return false;
        }

        $gm = new GoogleMaps();

        $supplier = $this->getSupplierModel();
        $supplierLatlng = $gm->getLatLng($supplier->address . ' ' . $supplier->address_2. ' ' . $supplier->zip);
        $customerLatlng = $gm->getLatLng($order->address . ' ' . $order->address_2. ' ' . $supplier->zip);
        $distance = $gm->getDistanceMatrix($supplierLatlng, $customerLatlng);

        $duration = '30 mins';

        $timeToTake = 0;

        if(is_null($order->supplier_id)) {
            $oq = OrderQuery::findOne(['order_id' => $order->id, 'supplier_id' => $this->supplierModel->id, 'order' => 0]);

            if(!($oq)) {
                Yii::$app->session->setFlash('success', 'Order access');
            } else {
                $timeToTake = $oq->time_start_query + Yii::$app->params['timeToTakeOrder'] - time();
            }
        }

        if ($distance['success'] == true) {
            $duration = $distance['duration'];
        }

        return ['duration' => $duration, 'estimation' => date('H:i', strtotime($duration)), 'timeToTake' => $timeToTake];
    }

    public function actionTakeOrder()
    {
        $post = Yii::$app->request->post();

        $order = $this->takeOrder($post);

        return $this->redirect(Url::toRoute('/supplier/show-order?l=') . $order->weblink);
    }

    private function complete($id)
    {
        if (!($order = Order::findOne($id))) {
            return false;
        }

        if ($order->status !== Order::ORDER_STATUS_IN_PROGRESS) {
            return false;
        }

        $number = User::find()->where(['id' => $order->customer_id])->one()->phone_number;
        $orderLink = Yii::$app->urlManager->createAbsoluteUrl(['site/order-rating', 'l' => $order->weblink]);
        $message = "Delivery completed.
        Please take a short survey about your delivery: $orderLink";

        Twilio::sendSms($number, $message);
        Log::orderLog($order->id, Yii::$app->user->getId(), 'Order complete');
        $order->status = Order::ORDER_STATUS_COMPLETE;
        return $order->save();
    }

    private function cancelOrder($id, $status)
    {
        $orderAllowedStatuses = [
            Order::ORDER_STATUS_IN_PROGRESS,
            Order::ORDER_STATUS_DELIVER_AT_PLACE,
            Order::ORDER_STATUS_DELIVER_NEAR_PLACE,
        ];

        $changeToAllowedStatuses = [
            Order::ORDER_STATUS_CANCELLED_BY_SUPPLIER,
            Order::ORDER_STATUS_CANCELLED_BY_DELIVER,
        ];

        if (!in_array($status, $changeToAllowedStatuses)) {
            return false;
        }

        $order = Order::find()
            ->where(['id' => $id])
            ->andWhere(['IN', 'status', $orderAllowedStatuses])
            ->andWhere(['supplier_id' => $this->supplierModel->id]);

        if (!$order->count()) {
            return false;
        }
        $order = $order->one();

        $number = User::find()->where(['id' => $order->customer_id])->one()->phone_number;
        Twilio::sendSms($number, Message::getText('delivery_failed_sms'));
        Log::orderLog($order->id, Yii::$app->user->getId(), 'Order canceled by supplier');
        $order->status = $status;
        $order->save();
    }
}
