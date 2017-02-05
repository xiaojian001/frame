<?php
namespace Weixin\Controller;
use Weixin\Common\CommonController;
use Common\Controller\JsonResults;
use Think\Controller;

class WxpayController extends Controller {

    /**
     * 微信支付确定页面
     */
    public function weixinSavePay()
    {
        $intUserId = $this->intUserId;
        $intOrder = I("get.order_id", 0);
        $arrOrderInfo = M("order")->where(Array('id' => $intOrder, 'member_id' => $intUserId))->find();

        if (!$arrOrderInfo) {
            JsonResults::errorResult("错误的订单编号。");
        }

        Vendor("Pay.Wxpay.WxPayPubHelper");
        $jsApi = new \JsApi_pub();
        $intCode = I("get.code");
        if($intCode=='')
        {
            $url1 = urlencode("/Wxpay/weixinSavePay&order_id=".$intOrder);
            //触发微信返回code码
            $url = $jsApi->createOauthUrlForCode($url1);
            Header("Location: $url");
        }
        //获取code码，以获取openid
        $jsApi->setCode($intCode);
        $openid = $jsApi->getOpenId();
        $unifiedOrder = new \UnifiedOrder_pub();
        $body = $arrOrderInfo["begin_address"] . "-" . $arrOrderInfo["end_address"];
        $unifiedOrder->setParameter("openid", "$openid");//商品描述
        $unifiedOrder->setParameter("body", $body);//商品描述

        $out_trade_no = $arrOrderInfo["order_number"];

        $unifiedOrder->setParameter("out_trade_no", "$out_trade_no");//商户订单号
        $unifiedOrder->setParameter("total_fee", "" . $arrOrderInfo['total_fee_money'] * 100);//总金额.注意单位是分
        $unifiedOrder->setParameter("notify_url", \WxPayConf_pub::NOTIFY_URL);//通知地址
        $unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型
        $unifiedOrder->setParameter("attach", "order_id=$out_trade_no");//附加数据
        $unifiedOrder->setParameter("product_id", $intOrder);//商品ID

        $prepay_id = $unifiedOrder->getPrepayId();

        //=========步骤3：使用jsapi调起支付============
        $jsApi->setPrepayId($prepay_id);

        $jsApiParameters = $jsApi->getParameters();
        //兼容前端为空出问题的情况
        $arrOrderInfo['pay_money'] = $arrOrderInfo['total_fee_money'];
        $this->assign("info", $arrOrderInfo);
        $this->assign("jsApiParameters", $jsApiParameters);
        $this->display();
    }

    /**
     * 退款接口
     */
    public function index(){
        $intOrderId = 2717;//I("post.order_id");
//        $floMoney = I("post.refund_money");
        $arrOrderInfo = M("order")->where(Array("id"=>$intOrderId))->find();
        Vendor("Pay.Wxpay.WxPayPubHelper");
        $unifiedOrder = new \Refund_pub();
        $unifiedOrder->setParameter("out_trade_no", $arrOrderInfo["order_number"]);//商户订单号
        $unifiedOrder->setParameter("out_refund_no", $arrOrderInfo["order_number"]);//商户订单号
        $unifiedOrder->setParameter("total_fee", "" . $arrOrderInfo['total_fee_money'] * 100);//总金额.注意单位是分
        $unifiedOrder->setParameter("refund_fee", "" . 0.01 * 100);//总金额.注意单位是分
        $unifiedOrder->setParameter("op_user_id", "1364879602");
        $arr = $unifiedOrder->getResult();
        var_dump($arr);die();
    }


    /**
     * 异步通知
     */
    public function notify(){
        M('data')->add(Array('data'=>2,'add_time'=>time()));
        Vendor("Pay.Wxpay.WxPayPubHelper");
        //使用通用通知接口
        $notify = new \Notify_pub();
        //存储微信的回调
        //$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $xml = file_get_contents("php://input");
        $notify->saveData($xml);
        $arrNotifyData = $notify->data;
        M('data')->add(Array('data'=>var_export($xml,true),'add_time'=>time()));
        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if($notify->checkSign() == FALSE){
            M('data')->add(Array('data'=>"通知验证失败",'add_time'=>time()));
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }

        //==商户根据实际情况设置相应的处理流程，此处仅作举例=======

        //以log文件形式记录回调信息
        //         $log_ = new Log_();
        $log_name= "./notify_url.log";//log文件路径

        log_result($log_name,"【接收到的notify通知】:\n".$xml."\n");

        if($notify->checkSign() == TRUE)
        {
            if ($notify->data["return_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                log_result($log_name,"【通信出错】:\n".$xml."\n");
            }
            elseif($notify->data["result_code"] == "FAIL"){
                //此处应该更新一下订单状态，商户自行增删操作
                log_result($log_name,"【业务出错】:\n".$xml."\n");
            }
            else{
                //此处应该更新一下订单状态，商户自行增删操作
                log_result($log_name,"【支付成功】:\n".$xml."\n");
            }

            //商户自行增加处理流程,
            //例如：更新订单状态
            //例如：数据库操作
            //例如：推送支付完成信息

            $arrOrderInfo = M("order")->where(Array('order_number' => $arrNotifyData['out_trade_no']))->find();
            if(!$arrOrderInfo)
            {
                M('data')->add(Array('data'=>"记录错误的订单编号",'add_time'=>time()));
                $notify->setReturnParameter("return_code","FAIL");//返回状态码
            }
            $objModel = M();
            $objModel->startTrans();
            $arrData = Array(
                'order_status'=>5,
            );
            $result = M("order")->where(Array('id'=>$arrOrderInfo['id']))->save($arrData);
            if($result===false)
            {
                $objModel->rollback();
                $notify->setReturnParameter("return_code","FAIL");//返回状态码
            }

            $floPayMoney = $arrOrderInfo['total_fee_money'];
            //司机收入
            $objAccount = New AccountService($arrOrderInfo['driver_id'],$floPayMoney);
            $arrResult = $objAccount->inputCommonMoney("收到车费",$arrOrderInfo['id']);
            if($arrResult[1] !=1)
            {
                M('data')->add(Array('data'=>"支付车费给司机失败",'add_time'=>time()));
                $objModel->rollback();
                $notify->setReturnParameter("return_code","FAIL");//返回状态码
            }

            //进行数据统计
            $intNumber = date("Ymd",$arrOrderInfo['access_time']);
            if($arrOrderInfo['order_model']==1)
            {
                $objFinance = new FinanceReportController();
                $arrResult = $objFinance->grabDataCount($floPayMoney,$arrOrderInfo['distance'],$intNumber,$arrOrderInfo['id']);
            }
            else
            {
                $objFinance = new FinanceReportController();
                $arrResult = $objFinance->assignDataCount($floPayMoney,$arrOrderInfo['distance'],$intNumber,$arrOrderInfo['id']);
            }
            if($arrResult[1] !=1)
            {
                M('data')->add(Array('data'=>"统计失败",'add_time'=>time()));
                $objModel->rollback();
                $notify->setReturnParameter("return_code","FAIL");//返回状态码
            }
            //发送站内信提醒
            $data = array(
                's_user_id' => 0,
                'r_user_id' => $arrOrderInfo['driver_id'],
                'title' => '订单已支付',
                'content' => "从{$arrOrderInfo['begin_address']}到{$arrOrderInfo['end_address']}的订单，共{$floPayMoney}元已经支付。",
                'add_time' => time(),
                'status' => 0
            );
            $intResult = M('member_message')->add($data);
            if ($intResult === false) {
                $objModel->rollback();
                JsonResults::errorResult("站内消息发送出错");

            }
            //socket通知
            $socketio = new SocketIO();
            $arrData = array("driver_id"=>$arrOrderInfo['driver_id']);
            if (!$socketio->send('120.76.114.98', 3000, 'didi_pay', json_encode($arrData))) {
                $objModel->rollback();
                //JsonResults::errorResult("添加订单失败，请重新下单。");
            }
            //发送短信
            $phone = M('member')->where(array('id'=>$arrOrderInfo['driver_id']))->getField('phone');
            $arr = Array(
                'phone'=>$phone,
                'params'=>Array(
                    "money"=>"".$floPayMoney
                ),
                'tpl'=>"SMS_13215705"
            );
            $objSms = new SmsController($arr);
            $objSms->index() ;
            M('data')->add(Array('data'=>8,'add_time'=>time()));
            $objModel->commit();
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
            $returnXml = $notify->returnXml();
            echo $returnXml;

        }
    }
}