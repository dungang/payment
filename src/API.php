<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2017/3/4
 * Time: 13:57
 */

namespace dungang\payment;



abstract class API
{
    protected $_attributes;

    /**
     * @var string crt 文件
     */
    public $privateKeyPath;

    /**
     * @var string 支付网关
     */
    public $apiGate;

    /**
     * @var string
     */
    public $notifyUrl;

    public function __construct($config = [])
    {
        foreach($config as $key=>$val) {
            $this->$key = $val;
        }
        $this->init();
    }

    public function init(){}

    public function getAttributes()
    {
        return $this->_attributes;
    }

    public function __set($name,$value){
        if ($name == '_attributes') {
            throw new PaymentException('property ' . $name . ' is protected');
        }
        if (property_exists($this,$name)) {
            $this->$name = $value;
        } else {
            $setter = 'set' . $name;
            if (method_exists($this,$setter)) {
                $this->$setter($value);
            } else {
                $this->_attributes[$name] = $value;
            }
        }
    }

    public function __get($name) {
        if (property_exists($this,$name)) {
            return $this->$name;
        } else {
            $getter = 'get' . $name;
            if (method_exists($this,$getter)) {
                return $this->$getter();
            } else {
                return $this->_attributes[$name];
            }
        }
    }

    /**
     * @return mixed
     */
    public abstract function sign();

    /**
     * @return mixed
     */
    public abstract function call();

    /**
     * @return array
     */
    public function execute(){
        $this->sign();
        return $this->call();
    }

    /**
     * @param $data string
     * @param $signType string
     * @return string
     * @throws PaymentException
     */
    public function openSSLSign($data,$signType='RSA') {
        if (file_exists($this->privateKeyPath)) {
            throw new PaymentException('Payment Private key  not found');
        }
        $keyContent = file_get_contents($this->privateKeyPath);
        if ($res = openssl_get_privatekey($keyContent)) {
            if ('RSA2' == $signType) {
                openssl_sign($data,$sign,$res,OPENSSL_ALGO_SHA256);
            } else {
                openssl_sign($data,$sign,$res);
            }
            openssl_free_key($res);
            return base64_encode($sign);
        }
        throw new PaymentException('Payment private key bad format');
    }

    public function executeCurl($url,$options,$headers)
    {

    }

    /**
     * @param $url
     * @param $charset
     * @param bool $post
     * @return mixed
     * @throws \Exception
     */
    public function curl($url,$charset,$post=true) {
        $ch = curl_init();
        $params = http_build_query($this->getAttributes());
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $headers = array('content-type: application/x-www-form-urlencoded;charset=' . $charset);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, $post);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new \Exception($response, $httpStatusCode);
            }
        }
        curl_close($ch);
        return $response;
    }
}