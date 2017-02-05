<?php
namespace Common\Controller;
use Think\Controller;

class JsonResults
{
    public function __construct() { }

    public static function data($data, $options = 0)
    {
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($data, $options));
    }

    public static function exception(\Exception $exception, $data = null)
    {
        $response = array(
            'success' => false,
            'code'    => $exception->getCode(),
            'message' => $exception->getMessage()
        );
        if ($data !== null) {
            $response['data'] = $data;
        }
        static::data($response);
    }


    public static function emptyPage()
    {
        static::data(array('total' => 0, 'rows' => array()));
    }


    public static function page($total = 0, $rows = array())
    {
        static::data(array('total' => $total, 'rows' => $rows));
    }


    public static function insert($result, $error = self::ERR_MSG_INSERT)
    {
        if ($result === false) {
            static::error($error);
        } else {
            static::data(array('success' => true, 'id' => $result));
        }
    }


    public static function update($result, $error = self::ERR_MSG_UPDATE)
    {
        if ($result === false) {
            static::error($error);
        } else {
            static::data(array('success' => true));
        }
    }


    public static function delete($result, $error = self::ERR_MSG_DELETE)
    {
        if ($result === false) {
            static::error($error);
        } else {
            static::data(array('success' => true));
        }
    }

    public static function returnResult($arrData){
        if(!is_array($arrData))
        {
            static::errorResult("格式错误哦");
        }
        if(isset($arrData['info']) && isset($arrData['status']))
        {
            static::data($arrData);
        }
        $response = array(
            'info' => $arrData[0],
            'status'    => $arrData[1],
            'data' =>  $arrData[2]
        );
        static::data($response);
    }
    public static function successResult($info,$data){
        $response = array(
            'info' => $info,
            'status'    => 1,
            'data' =>  $data
        );
        static::data($response);
    }
    public static function errorResult($info,$data,$code=0){
        $response = array(
            'info' => $info,
            'status'    => $code,
            'data' =>  $data
        );
        static::data($response);
    }
    public static function errorCode($info,$code=0){
        $response = array(
            'info' => $info,
            'status'    => $code,
            'data' =>  ''
        );
        static::data($response);
    }
    public static function loginOut(){
        $response = array(
            'info' => "登陆超时",
            'status'    => -99
        );
        static::data($response);
    }
}