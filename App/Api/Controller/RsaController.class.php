<?php
namespace Api\Controller;
use Think\Controller;
use Api\Common\ApiServiceController;

class RsaController extends ApiServiceController {
    public function index(){
        $arrData = $this->getData();
        $this->success("读取成功",$arrData);
    }
}