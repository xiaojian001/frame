<?php
namespace User\Common;
use Common\Controller\JsonResults;
class ApiClient {
    protected $baseUrl;
    protected $privateKey = null;
    protected $publicKey = null;


    function __construct($baseUrl, $privateKey, $publicKey)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->privateKey = openssl_pkey_get_private($privateKey);
        $this->publicKey = openssl_pkey_get_public($publicKey);
        if ($this->privateKey === false) {
            throw new \InvalidArgumentException('Invalid private key');
        }
        if ($this->publicKey === false) {
            throw new \InvalidArgumentException('Invalid public key');
        }
    }


    function __destruct()
    {
        if ($this->privateKey) {
            openssl_free_key($this->privateKey);
        }
        if ($this->publicKey) {
            openssl_free_key($this->publicKey);
        }
    }


    protected function encrypt($data, &$encrypted)
    {
        if(openssl_private_encrypt(serialize($data), $encrypted, $this->privateKey)) {
            $encrypted = base64_encode($encrypted);
            return true;
        }
        return false;
    }


    protected function decrypt($data, &$decrypted)
    {
        if(openssl_public_decrypt(base64_decode($data), $decrypted, $this->publicKey)) {
            $decrypted = unserialize($decrypted);
            return true;
        }
        return false;
    }


    public function execute($api, $params)
    {
        if(!$this->encrypt($params, $encrypted)) {
            JsonResults::errorCode('Client encryption error', -100);
        }
        $response = static::httpPost($this->baseUrl . '/' . $api, $encrypted);
        if ($response === false) {
            JsonResults::errorCode('Request error', -101);
        }
        if(!$this->decrypt($response, $data)) {
            JsonResults::errorCode('Client decryption error', -102);
        }
        if (!is_array($data)) {
            JsonResults::errorCode('Invalid response content', -101);
        }
        JsonResults::successResult("读取成功",$data);
    }


    protected static function httpPost($url, $data = null)
    {
        $ch = static::initCurl($url);
        if ($data === null) {
            curl_setopt($ch, CURLOPT_POST, true);
        } else {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, strval($data));
        }
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }


    protected static function initCurl($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        return $ch;
    }
}