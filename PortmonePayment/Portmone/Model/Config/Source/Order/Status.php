<?php
/**
 * Created by PhpStorm.
 * User: mxs
 * Date: 15.01.19
 * Time: 11:34
 */

namespace PortmonePayment\Portmone\Model\Config\Source\Order;


class Status extends \Magento\Sales\Model\Config\Source\Order\Status
{
    /**
     * @var string[]
     */
    protected $_stateStatuses = [
        \Magento\Sales\Model\Order::STATE_NEW,
        \Magento\Sales\Model\Order::STATE_PROCESSING,
        \Magento\Sales\Model\Order::STATE_COMPLETE,
        \Magento\Sales\Model\Order::STATE_CLOSED,
        \Magento\Sales\Model\Order::STATE_CANCELED,
        \Magento\Sales\Model\Order::STATE_HOLDED,
        \Magento\Sales\Model\Order::STATE_PENDING_PAYMENT,
    ];
}