<?php

namespace frontend\controllers;

use common\models\Order;
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

    public function actionIndex($takeOrder = 0, $cancelSupplier = 0, $cancelDeliver = 0) {
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

        $allowedToDeliver = Order::find()
            ->with('orderItems')
            ->with('customer')
            ->where(['status' => Order::ORDER_STATUS_NEW])
            ->asArray()
            ->all();

        $inProgress = Order::find()
            ->with('orderItems')
            ->with('customer')
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



        return $this->render('index', [
            'allowed' => $allowedToDeliver,
            'inProgress' => $inProgress,
            'finished' => $finished,
        ]);
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

    private function takeOrder($id) {
        if (!($order = Order::findOne($id))) {
            return false;
        }

        if($order->status !== Order::ORDER_STATUS_NEW) {
            return false;
        }

        $supplier = $this->getSupplierModel();
        $order->supplier_id = $supplier->id;
        $order->status = Order::ORDER_STATUS_IN_PROGRESS;
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
