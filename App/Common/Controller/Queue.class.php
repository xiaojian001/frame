<?php
/**
 * @author xj
 */
namespace Common\Controller;
use Think\Controller;

class Queue
{
    private $strKey = 'reg_tpl';
    private $phone;
    private $code;
    /**
     * 构造函数
     */
    public function __construct($strKey,$phone,$code)
    {
        $this->strKey =$strKey;
        $this->phone =$phone;
        $this->code = $code;
        if($this->strKey=='')
        {
            return Array("模板key不存在",0);
        }
        if(!isPhone($this->phone))
        {
            return Array("错误的手机号码。",0);
        }
    }

    /**
     * 添加短信到队列
     */
    public function addQueue($strKey='reg_tpl',$phone){
        //验证手机号码是否正确
        if(!isPhone($phone))
        {
            return Array("错误的手机号码。",0);
        }
        $this->code = rand(1000,9999);
        $arrWhere = Array(
            'phone'=>$phone,
            'key'=>$strKey,
            'status'=>0,
            'valid_time'=>Array('lt',time()),
        );
        $objVerify = M("web_verify_code");
        $arrVerifyCode = $objVerify->where($arrWhere)->find();
        $objVerify->startTrans();
        if($arrVerifyCode)
        {
            $this->code = $arrVerifyCode['code'];
            $intValidTime = strtotime("+5 minute");
            $result = $objVerify->where(Array('id'=>$arrVerifyCode['id']))->setField("valid_time",$intValidTime);
            if($result===false)
            {
                $objVerify->rollback();
                return Array("发送失败，请稍后再操作。",0);
            }
        }
        else
        {
            $arrData = Array(
                'phone'=>$phone,
                'key'=>$strKey,
                'code'=>$this->code,
                'add_time'=>time(),
                'valid_time'=>strtotime("+5 minute")
            );
            $result = $objVerify->add($arrData);
            if(!$result)
            {
                $objVerify->rollback();
                return Array("发送失败，请稍后再操作。",0);
            }
        }
        //执行发送短信操作
        $arrSms = Array(
            'code'=>$this->code,
            'phone'=>$phone,
            'key'=>$strKey,
        );
        $arrResult = $this->sendSms($arrSms);
        if(!$arrResult)
        {
            $objVerify->rollback();
            return Array("发送失败，请稍后再操作。",0);
        }
        $objVerify->commit();
        return Array('info'=>"发送完成",'status'=>1,'data'=>$this->code);
    }

    /**
     * 执行发送短信
     */
    public function sendSms($arrData){
        $arr = Array(
            'code'=>$arrData['code'],
            'phone'=>$arrData['phone'],
            'tpl'=>"SMS_13027070"
        );
        $objSms = new SmsController($arr);
        return $objSms->index();
    }

    /**
     * 验证手机验证码
     */
    public function validSms(){
        $arrWhere = Array(
            'phone'=>$this->phone,
            'key'=>$this->strKey,
            'code'=>$this->code,
            //'valid_time'=>Array('lt',time()),
            'status'=>0
        );
        $arrVerifyCode = M("web_verify_code")->where($arrWhere)->find();
        if(!$arrVerifyCode)
        {
            return Array("验证失败，验证码错误。",0);
        }
        if($arrVerifyCode['valid_time']<time())
        {
            return Array("验证超时，请重新验证。",0);
        }
        M("web_verify_code")->where(Array('id'=>$arrVerifyCode['id']))->setField("status",1);
        return Array("验证成功。",1);
    }

}