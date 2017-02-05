<?php
namespace Common\Controller;

use Think\Controller;

/**
 * 百度轨迹相关操作类
 * @package Common
 */
class BaiduMap extends Controller
{
    private $ak;
    private $serviceId;

    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        $this->ak = C('BaiduMap.ApiKey');
        $this->serviceId = C('BaiduMap.ServiceId');
    }

    /*
     * 添加或删除百度鹰眼实体
     * $name：str|添加或删除的实体名称
     * $strType：str|add代表添加实体，delete代表删除
     * return array('status'=>'','message'=>'')
     *        status：0代表成功，其他代表失败
     *        message：返回的提示消息
     * @.xw
     */
    public function entityManager($name, $strType='add')
    {
        $uri = 'http://api.map.baidu.com/trace/v2/entity/' . $strType;
        $data = array(
            'ak' => $this->ak,
            'service_id' => $this->serviceId,
            'entity_name' => $name
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $return = curl_exec($ch);
        curl_close($ch);
        return json_decode($return, true);
    }

    /*
     * 上传一个轨迹节点
     * $entity:实体名称
     * $lng:double|经度
     * $lat:double|纬度
     * $coordType:地图标准（1：GPS经纬度坐标2：国测局加密经纬度坐标 3：百度加密经纬度坐标。），默认为1：GPS标准
     * $columnKey:自定义内容，可以为空
     * return array('status'=>'','message'=>'')
     *        status：0代表成功，其他代表失败
     *        message：返回的提示消息
     */
    public function addTrack($entity, $lng, $lat, $coordType = 3, $columnKey = '')
    {
        $uri = 'http://api.map.baidu.com/trace/v2/track/addpoint';
        $data = array(
            'ak' => $this->ak,
            'service_id' => $this->serviceId,
            'entity_name' => $entity,
            'longitude' => $lng,//经度
            'latitude' => $lat,//纬度
            'coord_type' => $coordType,//
            'loc_time' => time(),
            'column-key' => $columnKey//自定义，可以为空
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $return = curl_exec($ch);
        curl_close($ch);
        return $return;
    }

    public function returnDistance($strOrigin,$strDestination){
        $uri = 'http://api.map.baidu.com/direction/v1?';
        $data = array (
            'ak' => $this->ak,
            'origin' => $strOrigin,
            'destination' =>$strDestination,
            'mode' => "riding",
            'origin_region' => "南宁",
            'destination_region' => "南宁",
            'output' => "json",
        );
        $url = $uri.http_build_query($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        $arrInfo = json_decode(curl_exec($ch),true) ;
        if(empty($arrInfo) &&is_array($arrInfo) && empty($arrInfo['result']['routes'][0]['distance']))
        {
            JsonResults::returnResult(Array("地址地图无法获取，请重新选择。",0));
        }
        $floDistance = $arrInfo['result']['routes'][0]['distance'];//距离
        return $floDistance;
        //JsonResults::returnResult(Array("返回成功",1,Array('distance'=>$floDistance)));
    }


}