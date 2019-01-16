<?php
namespace PortmonePayment\Portmone\Block;

use Magento\Framework\View\Element\Template;


/**
 * Class PaymentInfo
 *
 * @package PortmonePayment\Portmone\Block
 */
class PaymentInfo  extends Template
{
    /** @var \PortmonePayment\Portmone\Helper\PaymentHelper $paymentHelper */
    protected $paymentHelper;

    public function __construct(
        Template\Context $context,
        \PortmonePayment\Portmone\Helper\PaymentHelper $paymentHelper,
        array $data = []
    ) {
        $this->paymentHelper = $paymentHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return \PortmonePayment\Portmone\Helper\PaymentHelper
     */
    public function getHelper () {
        return $this->paymentHelper;
    }


    public function getOrderId () {
        return $this->paymentHelper->getOrderId(
            $this->getRequest()->getPostValue()
        );
    }

    public function getSuccess () {
        return $this->paymentHelper->getSuccess(
            $this->getRequest()->getPostValue()
        );
    }

    public function getReceiptUrl () {
        return $this->paymentHelper->getReceiptUrl(
            $this->getRequest()->getPostValue()
        );
    }
}