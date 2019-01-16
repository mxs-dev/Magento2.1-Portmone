<?php
namespace PortmonePayment\Portmone\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Model\Order;
use Magento\Framework\App\Helper\AbstractHelper;


/**
 * Class PaymentHelper
 *
 * @package PortmonePayment\Portmone\Helper
 */
class PaymentHelper extends AbstractHelper
{
    /** Payment response fields */
    const TRANSACTION_ID = 'SHOPBILLID';
    const ORDER_NUMBER   = 'SHOPORDERNUMBER';
    const APPROVE_CODE   = 'APPROVALCODE';
    const BILL_AMOUNT    = 'BILL_AMOUNT';
    const RESULT         = 'RESULT';
    const RECEIPT_URL    = 'RECEIPT_URL';

    const XML_PATH_NEW_ORDER_STATE = 'payment/portmone/order_status';
    const XML_PATH_PAYMENT_SUCCESS_ORDER_STATE = 'payment/portmone/payment_success_order_status';
    const XML_PATH_PAYMENT_SUCCESS_MESSAGE = 'payment/portmone/payment_success_message';
    const XML_PATH_PAYMENT_ERROR_ORDER_STATE = 'payment/portmone/payment_error_order_status';
    const XML_PATH_PAYMENT_ERROR_MESSAGE = 'payment/portmone/payment_error_message';
    const XML_PATH_PAYMENT_NOTIFY = 'payment/portmone/payment_notify';
    const XML_PATH_ACTION_URL = 'payment/portmone/action_url';
    const XML_PATH_PAYEE_ID = 'payment/portmone/payee_id';

    /** @var \Magento\Store\Model\StoreManagerInterface $storeManager */
    protected $storeManager;

    /** @var \Magento\Sales\Model\OrderFactory $orderFactory */
    protected $orderFactory;

    /**
     * PaymentHelper constructor.
     *
     * @param Context $context
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->orderFactory = $orderFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * @param array $data
     * @return string
     */
    public function getOrderId ($data) {
        $orderNumber = $data[static::ORDER_NUMBER];
        return $orderNumber ? explode('_', $orderNumber)[0] : null;
    }

    public function getActionUrl () {
        return $this->scopeConfig->getValue(static::XML_PATH_ACTION_URL, 'store');
    }


    public function getPayeeId () {
        return  $this->scopeConfig->getValue(static::XML_PATH_PAYEE_ID, 'store');
    }


    public function getAmount ($orderId) {
        $orderFactory = $this->orderFactory;
        $order = $orderFactory->create()->loadByIncrementId($orderId);
        return $order->getGrandTotal();
    }


    /**
     * Returns specific Portmone data for payment gateway.
     *
     * @param $orderId
     * @return array
     */
    public function getPostData($orderId)
    {
        $baseUrl = $this->storeManager->getStore()->getBaseUrl();
        return [
            'payee_id'          => $this->getPayeeId(),
            'shop_order_number' => $orderId . '_' . time(),
            'description' => __("Payment for order #%1", $orderId),
            'bill_amount' => round($this->getAmount($orderId), 2),
            'success_url' => $baseUrl . 'portmone/payment/index',
            'failure_url' => $baseUrl . 'portmone/payment/index',
            'lang' => 'ru',
        ];
    }

    /**
     * @param array $data
     * @return string | null
     */
    public function getReceiptUrl ($data) {
        return $data[static::RECEIPT_URL];
    }

    /**
     * @param array $data
     * @return bool
     */
    public function getSuccess ($data) {
        return strlen($data[static::RESULT]) === 1;
    }


    /**
     * @return bool
     */
    public function getPaymentNotify () {
        return $this->scopeConfig->getValue(static::XML_PATH_PAYMENT_NOTIFY);
    }


    /**
     * @return string
     */
    public function getNewOrderStatus () {
        return $this->scopeConfig->getValue(static::XML_PATH_NEW_ORDER_STATE);
    }


    /**
     * @return string
     */
    public function getPaymentSuccessOrderState () {
        return $this->scopeConfig->getValue(static::XML_PATH_PAYMENT_SUCCESS_ORDER_STATE);
    }


    /**
     * @return string
     */
    public function getPaymentSuccessMessage () {
        return $this->scopeConfig->getValue(static::XML_PATH_PAYMENT_SUCCESS_MESSAGE, 'store');
    }


    /**
     * @return string
     */
    public function getPaymentErrorOrderState () {
        return $this->scopeConfig->getValue(static::XML_PATH_PAYMENT_ERROR_ORDER_STATE);
    }


    /**
     * @return string
     */
    public function getPaymentErrorMessage () {
        return $this->scopeConfig->getValue(static::XML_PATH_PAYMENT_ERROR_MESSAGE, 'store');
    }
}