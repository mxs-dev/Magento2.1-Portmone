<?php
namespace PortmonePayment\Portmone\Controller\Payment;

use Magento\Framework\App\Action\Context;

/**
 * Class Index
 *
 * @package PortmonePayment\Portmone\Controller\Payment
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /** @var \PortmonePayment\Portmone\Model\Portmone $paymentHelper */
    protected $paymentHelper;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param \PortmonePayment\Portmone\Model\Portmone $paymentHelper
     */
    public function __construct(
        Context $context,
        \PortmonePayment\Portmone\Model\Portmone $paymentHelper
    ) {
        $this->paymentHelper = $paymentHelper;
        parent::__construct($context);
    }


    /** @inheritdoc */
    public function execute()
    {
        /** @var array $data */
        $data = $this->getRequest()->getPostValue();
        $this->paymentHelper->process($data);

        return $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
    }
}