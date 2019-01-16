<?php
namespace PortmonePayment\Portmone\Model;

use Magento\Sales\Model\Order;
use Magento\Payment\Model\Method\AbstractMethod;
use PortmonePayment\Portmone\Helper\PaymentHelper;


/**
 * Class Portmone
 *
 * @package PortmonePayment\Portmone\Model
 */
class Portmone extends AbstractMethod
{
    protected $_isInitializeNeeded = true;
    protected $_code = 'portmone';
    protected $_isOffline = true;
    protected $_formBlockType = 'PortmonePayment\Portmone\Block\Form\Portmone';
    protected $_infoBlockType = 'Magento\Payment\Block\Info\Instructions';
    protected $_actionUrl = "https://www.portmone.com.ua/gateway/";
    protected $_test;
    protected $orderFactory;

    /** @var \Magento\Sales\Model\Order\Email\Sender\OrderCommentSender $orderCommentSender */
    protected $orderCommentSender;

    /** @var \PortmonePayment\Portmone\Helper\PaymentHelper $paymentHelper */
    protected $paymentHelper;

    /**
     * Portmone constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Payment\Model\Method\Logger $logger
     * @param \Magento\Framework\Module\ModuleListInterface $moduleList
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param PaymentHelper $paymentHelper
     * @param Order\Email\Sender\OrderCommentSender $orderCommentSender
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct (
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \PortmonePayment\Portmone\Helper\PaymentHelper $paymentHelper,
        \Magento\Sales\Model\Order\Email\Sender\OrderCommentSender $orderCommentSender,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->orderCommentSender = $orderCommentSender;
        $this->orderFactory = $orderFactory;
        $this->paymentHelper = $paymentHelper;
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $resource,
            $resourceCollection,
            $data
        );
    }

    public function initialize($paymentAction, $stateObject)
    {
        $this->_actionUrl = $this->getConfigData('action_url');
        $this->_test = $this->getConfigData('test');
        $stateObject->setState(Order::STATE_NEW);
        $stateObject->setStatus($this->paymentHelper->getNewOrderStatus());
        $stateObject->setIsNotified(false);
    }

    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        return true;
    }

    public function getActionUrl()
    {
        return $this->_actionUrl;
    }


    public function getInstructions()
    {
        return trim($this->getConfigData('instructions'));
    }

    public function getAmount($orderId)
    {
        $orderFactory = $this->orderFactory;
        $order = $orderFactory->create()->loadByIncrementId($orderId);
        return $order->getGrandTotal();
    }

    protected function getOrder($orderId)
    {
        $orderFactory = $this->orderFactory;
        return $orderFactory->create()->loadByIncrementId($orderId);
    }
    protected function isCarrierAllowed($shippingMethod)
    {
        return strpos($this->getConfigData('allowed_carrier'), $shippingMethod) !== false;
    }

    public function getCurrencyCode($orderId)
    {
        return $this->getOrder($orderId)->getBaseCurrencyCode();
    }

    /**
     * Returns specific Portmone data for payment gateway.
     *
     * @param $orderId
     * @return array
     */
    public function getPostData($orderId)
    {
        return $this->paymentHelper->getPostData($orderId);
    }


    /**
     * Process response from PortmonePayment gateway.
     *
     * @param $response
     * @throws
     */
    public function process($response)
    {
        $order = $this->getOrder($this->paymentHelper->getOrderId($response));
        $this->_processOrder($order, $response);
    }


    /**
     * Process Order by returned from gateway data.
     *
     * @param Order $order
     * @param $response
     * @throws \Exception
     */
    protected function _processOrder(\Magento\Sales\Model\Order $order, $response)
    {
        if ($this->paymentHelper->getSuccess($response)) {
            $state   = $this->paymentHelper->getPaymentSuccessOrderState();
            $message = $this->paymentHelper->getPaymentSuccessMessage();
        } else {
            $state = $this->paymentHelper->getPaymentErrorOrderState();
            $message = $this->paymentHelper->getPaymentErrorMessage();
        }

        $order->setState($state)->setStatus($state);
        $order->addStatusToHistory($order->getStatus(), $message, $this->paymentHelper->getPaymentNotify());

        if ($this->paymentHelper->getPaymentNotify()) {
            $this->orderCommentSender->send($order, true, $message);
        }

        $order->save();
    }
}
