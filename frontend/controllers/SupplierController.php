<?php

namespace frontend\controllers;

use common\models\GoogleMaps;
use common\models\Order;
use common\models\Product;
use common\models\Rating;
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
        parent::beforeAction($action);
        $actionId = Yii::$app->controller->action->id;

        if (!$this->isSupplierDataFilled() && !in_array($actionId, ['confirm'])) {
            return $this->redirect('confirm')->send();
        }

        $this->supplierModel = $this->getSupplierModel();

        if (!$this->supplierModel->is_active && !in_array($actionId, ['need-activation', 'confirm'])) {
            return $this->redirect('need-activation')->send();
        }

        return true;
    }

    public function actionNeedActivation() {
        if ($this->supplierModel->is_active) {
            return $this->redirect('index')->send();
        }

        return $this->render('need-activation');
    }

    public function actionIndex($takeOrder = 0, $cancelSupplier = 0, $cancelDeliver = 0, $complete = 0) {
        if ($takeOrder) {
            $this->takeOrder($takeOrder);
            $this->redirect('index');
        }

        if ($cancelSupplier) {
            $this->cancelOrder($cancelSupplier, Order::ORDER_STATUS_CANCELLED_BY_SUPPLIER);
            $this->redirect('index');
        }

        if ($cancelDeliver) {
            $this->cancelOrder($cancelDeliver, Order::ORDER_STATUS_CANCELLED_BY_SUPPLIER);
            $this->redirect('index');
        }

        if ($complete) {
            $this->complete($complete);
            $this->redirect('index');
        }

        $allowedToDeliver = Order::find()
            ->with('orderItems')
            ->with('customer')
            ->with('supplier')
            ->where(['status' => Order::ORDER_STATUS_NEW])
            ->asArray()
            ->all();

        $inProgress = Order::find()
            ->with('orderItems')
            ->with('customer')
            ->with('supplier')
            ->where(['IN', 'status',
            [
                Order::ORDER_STATUS_IN_PROGRESS,
                Order::ORDER_STATUS_DELIVER_NEAR_PLACE,
                Order::ORDER_STATUS_DELIVER_AT_PLACE,
            ]])
            ->andWhere(['supplier_id' => $this->supplierModel->id])
            ->asArray()
            ->all();

        $finished = Order::find()
            ->with('orderItems')
            ->with('customer')
            ->with('supplier')
            ->where(['IN', 'status',
            [
                Order::ORDER_STATUS_COMPLETE,
                Order::ORDER_STATUS_CANCELLED,
                Order::ORDER_STATUS_CANCELLED_BY_CUSTOMER,
                Order::ORDER_STATUS_CANCELLED_BY_DELIVER,
                Order::ORDER_STATUS_CANCELLED_BY_SUPPLIER,
            ]])
            ->andWhere(['supplier_id' => $this->supplierModel->id])
            ->asArray()->all();

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
            'earnings' => Order::find()->where(['supplier_id' => $this->supplierModel->id])->andWhere(['status' => Order::ORDER_STATUS_COMPLETE])->count(),
            'accepted' => Order::find()->where(['supplier_id' => $this->supplierModel->id])->count(),
            'mounthlyEarnings' => Order::find()
                ->select('SUM(total) as total')
                ->where(['supplier_id' => $this->supplierModel->id])
                ->andWhere(['status' => Order::ORDER_STATUS_COMPLETE])
                ->andWhere(['>','created_at', date('Y-m-d H:i:s', strtotime('today - 30 days'))])
                ->one()->total
        ]);
    }

    function getNeedleArray($source, $keys = []) {
        $result = [];

        foreach ($keys as $key) {
            if (is_array($source[$key]) && count($source[$key])){
                $result[$key] = $source[$key][0];
            }
        }

        return $result;
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

    private function getSupplierModel() {
        return Supplier::findOne(['supplier_id' => Yii::$app->user->getId()]);
    }

    private function takeOrder() {
        if (!($order = Order::findOne(['id' => Yii::$app->request->post('id')]))) {
            return false;
        }

        if($order->status !== Order::ORDER_STATUS_NEW) {
            return false;
        }

        $supplier = $this->getSupplierModel();

        $duration = Yii::$app->request->post(['deliveryTime']). ' mins';
        $order->deliver_name = Yii::$app->request->post('deliverName');
        $order->delivery_duration = date('h:iA', strtotime($duration)) . ' | ' . $duration;
        $order->supplier_id = $supplier->id;
        $order->status = Order::ORDER_STATUS_IN_PROGRESS;
        return $order->save();
    }

    public function actionCalculateDelivery($id) {
        Yii::$app->response->format = 'json';

        if (!($order = Order::findOne($id))) {
            return false;
        }

        if($order->status !== Order::ORDER_STATUS_NEW) {
            return false;
        }

        $gm = new GoogleMaps();

        $supplier = $this->getSupplierModel();
        $supplierLatlng = $gm->getLatLng($supplier->address . ' ' . $supplier->address_2);
        $customerLatlng = $gm->getLatLng($order->address . ' ' . $order->address_2);
        $distance = $gm->getDistanceMatrix($supplierLatlng, $customerLatlng);

        $duration = '30 mins';

        if($distance['success'] == true) {
            $duration = $distance['duration'];
        }

        return ['duration' => $duration];
    }

    public function actionTakeOrder() {
        $post = Yii::$app->request->post();

        $this->takeOrder($post);

        return true;
    }

    private function complete($id) {
        if (!($order = Order::findOne($id))) {
            return false;
        }

        if($order->status !== Order::ORDER_STATUS_IN_PROGRESS) {
            return false;
        }

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

        if(!$order->count()) {
            return false;
        }

        $order = $order->one();
        $order->status = $status;
        $order->save();
    }
}
