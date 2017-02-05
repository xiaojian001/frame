<?php
namespace User\Controller;
use Think\Controller;
use Common\Controller\WeixinTvSdk;
use Common\Controller\JsonResults;
use Common\Controller\SocketIO;


class SocketController extends Controller {
   public function index(){
       $socketio = new SocketIO();
       if (!$socketio->send('192.168.154.128', 2120, 'xiaojian',"service-php")) {
           var_dump($socketio->error);
       }
   }

    public function test1(){
        $to_uid = '1478177566000';
        // 推送的url地址，上线时改成自己的服务器地址
        $push_api_url = "http:/192.168.154.128:2121/";
        $post_data = array(
            'type' => 'publish',
            'content' => '这个是推送的测试数据',
            'to' => $to_uid,
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
        $return = curl_exec ( $ch );
        curl_close ( $ch );
        var_export($return);
    }
}