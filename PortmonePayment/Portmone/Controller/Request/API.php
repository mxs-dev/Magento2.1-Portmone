<?php

namespace PortmonePayment\Portmone\Controller\Request;

use Magento\Framework\App\Action\Context;
use \Magento\Framework\App\Request\Http;
use \PortmonePayment\Portmone\Model\Portmone;


/**
 * Class API
 *
 * @package PortmonePayment\Portmone\Controller\Request
 */
class API extends \Magento\Framework\App\Action\Action
{
    public    $http;
    protected $portmone;

    public function __construct(
        Context $context,
        Http $http,
        Portmone $portmone
    ) {
        $this->portmone = $portmone;
        $this->http = $http;
        parent::__construct($context);
    }

    public function execute()
    {
        echo $this->getIkSign();
        exit;
    }

    public function getIkSign(){
       $post = $this->getPost();
        if($post){
           return $this->portmone->IkSignFormation($post,$this->portmone->getConfigData('secret_key'));
        } else {
            return [
                'error'=>'something wrong in Sign Formation'
            ];
        }
    }

    public function getPost(){
        if(!empty($_POST) && !empty($_POST['ik_co_id'])){
            return $this->http->getPost();
        }else{
            return false;
        }
    }
}