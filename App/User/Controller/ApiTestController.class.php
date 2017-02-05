<?php
namespace User\Controller;
use Think\Controller;
use User\Common\ApiClient;


class ApiTestController extends Controller {
   public function index(){
      $objApi = new ApiClient("www.frame.mm/api",C("Api.rsa_private_key"),C("Api.rsa_public_key"));
      $arrResult = $objApi->execute('rsa/index', ['a' => time(),'b'=>100]);
      print_r($arrResult);die();
   }
}