<?php
namespace PortmonePayment\Portmone\Block\Widget;

use \Magento\Framework\View\Element\Template;


/**
 * Class Redirect
 *
 * @package PortmonePayment\Portmone\Block\Widget
 */
class Redirect extends Template
{
    protected $Config;
    protected $_checkoutSession;
    protected $_customerSession;
    protected $_orderFactory;
    protected $_orderConfig;
    protected $_template = 'html/portmone.phtml';
    protected $httpContext;

    /**
     * Redirect constructor.
     *
     * @param Template\Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Sales\Model\Order\Config $orderConfig
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \PortmonePayment\Portmone\Model\Portmone $paymentConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Sales\Model\Order\Config $orderConfig,
        \Magento\Framework\App\Http\Context $httpContext,
        \PortmonePayment\Portmone\Model\Portmone $paymentConfig,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_checkoutSession = $checkoutSession;
        $this->_customerSession = $customerSession;
        $this->_orderFactory = $orderFactory;
        $this->_orderConfig = $orderConfig;
        $this->_isScopePrivate = true;
        $this->httpContext = $httpContext;
        $this->Config = $paymentConfig;
    }

    /**
     * @return string
     */
    public function getActionUrl()
    {
        return $this->Config->getActionUrl();
    }

    /**
     * @return float | null
     */
    public function getAmount()
    {
        $orderId = $this->_checkoutSession->getLastOrderId();
        if ($orderId) {
            $incrementId = $this->_checkoutSession->getLastRealOrderId();

            return $this->Config->getAmount($incrementId);
        }

        return null;
    }

    /**
     * @return array | null
     */
    public function getPostData()
    {
        $orderId = $this->_checkoutSession->getLastOrderId();
        if ($orderId) {
            $incrementId = $this->_checkoutSession->getLastRealOrderId();

            return $this->Config->getPostData($incrementId);
        }

        return null;
    }

    /*public function getPaymentSystems(){

        return $this->Config->getPaymentSystems();
    }*/
    /*public function ActiveAPI(){
        return $this->Config->isAPIAvailable();
    }*/
    /*public function getStoreCurrency(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        return $storeManager->getStore()->getCurrentCurrency()->getCode();
    }*/

    /*public function getImage($image){
        return $this->getViewFileUrl('PortmonePayment_Portmone::images/'. $image .'.png');
    }*/

    /*public function getAPIUrl(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $baseurl = $storeManager->getStore()->getBaseUrl();
        return $baseurl . 'portmone/request/api';
    }*/
}
