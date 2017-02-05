<?php
namespace User\Controller;
use Think\Controller;
use Common\Controller\WeixinTvSdk;
use User\Common\CommonController;

class JpushController extends CommonController {
   public function index(){
       Vendor('Push.autoload');
       $app_key = '17832af09d274d413ae03776';
       $master_secret = '0d6f626775e83e3129ef819b';
       $registration_id = '';//getenv('registration_id');
       $client = new \JPush\Client($app_key, $master_secret);
       $push_payload = $client->push()
           ->setPlatform('all')
           ->addAlias('aaa')
//    ->addAllAudience()
           ->setNotificationAlert('Hi, JPush');
       try {
           $response = $push_payload->send();
       }catch (\JPush\Exceptions\APIConnectionException $e) {
           // try something here
           print $e;
       } catch (\JPush\Exceptions\APIRequestException $e) {
           // try something here
           print $e;
       }
       print_r($response);die();
   }
}