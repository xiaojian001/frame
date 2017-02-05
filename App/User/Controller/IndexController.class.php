<?php
namespace User\Controller;
use Think\Controller;
use Common\Controller\WeixinTvSdk;

class IndexController extends Controller {
    public function index(){
        echo "1";die();
        //$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    public function programSeries(){
        $objWeixinSdk = new WeixinTvSdk(C("WEIXINTV"));
        $strToken = $objWeixinSdk->programSeries();
        print_r($strToken);die();
    }

    public function meterial(){
        $objWeixinSdk = new WeixinTvSdk(C("WEIXINTV"));
        $strToken = $objWeixinSdk->meterial();
        print_r($strToken);die();
    }
    public function getMeterial(){
        $id = 43490;
        $objWeixinSdk = new WeixinTvSdk(C("WEIXINTV"));
        $strToken = $objWeixinSdk->getMeterial($id);
        print_r($strToken);die();
    }
    public function curProgramList(){
        $objWeixinSdk = new WeixinTvSdk(C("WEIXINTV"));
        $strToken = $objWeixinSdk->curProgramList();
        print_r($strToken);die();
    }

    public function phone(){
        $arrOrder = M("order")->field("id,consigner_name,consigner_telephone")->where(Array("consigner_telephone"=>Array("NEQ",'')))->order("id")->select();
        $objModel = M();
        $objModel->startTrans();
        foreach ($arrOrder as &$v){
            $arrData = Array(
                "mobile_phone"=>$v['consigner_telephone'],
                "client_name"=>$v['consigner_name'],
            );
            $result = M("client")->add($arrData);
            if($result===false){
                $objModel->rollback();
                echo 111;die();
            }
        }

        $objModel->commit();
        echo 222;die();
    }
}