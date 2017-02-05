<?php
/**
 * 阿里大鱼API接口（短信接口）范例
 *
 * @author Flc <2016-02-18 23:18:10>
 * @link http://flc.ren
 * @link https://code.csdn.net/flc1125/alidayu
 */
namespace Common\Controller;

use Think\Controller;
use Alidayu\AlidayuClient as Client;
use Alidayu\Request\SmsNumSend;

class SmsController extends Controller
{
    private $code = null;
    private $phone = null;
    private $tpl = 'SMS_13027070';
    public function __construct($arr)
    {
        parent::__construct();
        $this->code = "".$arr['code'];
        $this->phone = "".$arr['phone'];
        $this->tpl =$arr['tpl']?"".$arr['tpl']:"SMS_13027070";
    }

    /**
     * 阿里大雨demo
     * @return [type] [description]
     */
    public function index()
    {
        return true;
        $client  = new Client;
        $request = new SmsNumSend;

        // 短信内容参数
        $smsParams = [
            'code'    =>  $this->code,
        ];

        // 设置请求参数
        $req = $request->setSmsTemplateCode($this->tpl)
            ->setRecNum($this->phone)
            ->setSmsParam(json_encode($smsParams))
            ->setSmsFreeSignName('寻舟出行')
            ->setSmsType('normal')
            ->setExtend('demo');

        $arrResult = $client->execute($req);
        if($arrResult['alibaba_aliqin_fc_sms_num_send_response']['result']['success']==1)
        {
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * 获取随机位数数字
     * @param  integer $len 长度
     * @return string
     */
    protected static function randString($len = 4)
    {
        $chars = str_repeat('0123456789', $len);
        $chars = str_shuffle($chars);
        $str   = substr($chars, 0, $len);
        return $str;
    }
}