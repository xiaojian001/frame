<?php
namespace Api\Common;
use Think\Controller;


class ApiServiceController extends Controller {
    private $data;
    public function __construct()
    {
        parent::__construct();
        $this->privateKey = openssl_pkey_get_private(C('Api.rsa_private_key'));
        $this->publicKey = openssl_pkey_get_public(C('Api.rsa_public_key'));
        if ($this->privateKey === false) {
            throw new \InvalidArgumentException('Invalid private key');
        }
        if ($this->publicKey === false) {
            throw new \InvalidArgumentException('Invalid public key');
        }
        $this->data = $this->handle();
    }

    public function handle($data = null)
    {
        if($data === null) {
            $data = file_get_contents('php://input');
        }
        if($this->decrypt($data, $output)) {
            return $output;
        }
        exit('Decryption Error');
    }

    public function success($message,$data){
        $response = array(
            'success' => true,
            'message' => $message
        );
        if ($data !== null) {
            $response['data'] = $data;
        }
        header('Content-Type:application/json; charset=utf-8');
        if($this->encrypt($data, $output)) {
            exit($output);
        }
        exit('Encryption Error');
    }
    protected function decrypt($data, &$decrypted)
    {
        if(openssl_public_decrypt(base64_decode($data), $decrypted, $this->publicKey)) {
            $decrypted = unserialize($decrypted);
            return true;
        }
        return false;
    }

    protected function encrypt($data, &$encrypted)
    {
        if(openssl_private_encrypt(serialize($data), $encrypted, $this->privateKey)) {
            $encrypted = base64_encode($encrypted);
            return true;
        }
        return false;
    }

    protected function getParam($name, $default = null)
    {
        if (is_array($this->data) && isset($this->data[$name])) {
            return $this->data[$name];
        }
        return $default;
    }

    protected function getParams($items)
    {
        $result = array();
        foreach ((array)$items as $key => $item) {
            $is_int = is_int($key);
            $is_array = is_array($item);
            $field = $is_int ? $item : $key;
            $default = $is_int ? null : ($is_array ? (isset($item[0]) ? $item[0] : null) : $item);
            $filter = ($is_int || !$is_array) ? null : (isset($item[1]) ? $item[1] : null);
            if (isset($this->data[$field])) {
                if ($filter != null && function_exists($func = $filter . 'val')) {
                    $result[$field] = call_user_func($func, $this->data[$field]);
                } else {
                    $result[$field] = $this->data[$field];
                }
            } else {
                $result[$field] = $default;
            }
        }
        return $result;
    }
    protected function getData()
    {
        return $this->data;
    }

}