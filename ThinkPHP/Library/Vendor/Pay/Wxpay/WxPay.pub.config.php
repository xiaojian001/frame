<?php
/**
* 	配置账号信息
*/

class WxPayConf_pub
{
	//=======【基本信息设置】=====================================
	//微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
	const APPID = 'wx481fa15fb9ed6533';
	//受理商ID，身份标识
	const MCHID = '1364879602';
	//商户支付密钥Key。审核通过后，在微信发送的邮件中查看
	const KEY = 'c4d3dd2961dbbfcb8f7f3659bd331fa0';
	//JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
	const APPSECRET = '4a857e6e9c85157bca7b73103b6036dc';
	const JS_API_CALL_URL = 'http://yt.ugogogo.cn/Weixin/Order/weixinSavePay' ;
	
	
	
	const SSLCERT_PATH = '/cacert/apiclient_cert.pem';
	const SSLKEY_PATH = '/cacert/apiclient_key.pem';

	const NOTIFY_URL = 'http://yt.ugogogo.cn/Weixin/WxPay/notify';
	const CURL_TIMEOUT = 60;
}

	
?>