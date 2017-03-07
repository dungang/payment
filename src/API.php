<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2017/3/4
 * Time: 13:57
 */

namespace dungang\payment;


/**
 * Class API
 * @package dungang\payment
 *
 * @property string $api_gate 支付网关
 * @property integer $timeout 执行超时时间，默认30s
 */
abstract class API
{
    const API_CREATE = 'Create';
    const API_CLOSE = 'Close';
    const API_REFUND = 'Refund';
    const API_REFUND_QUERY = 'RefundQuery';

    /**
     * @var array
     */
    protected $_attributes = [];

    /**
     * @var array http 请求除外的参数
     */
    private $except_params = [];


    public function __construct($config = [])
    {
        foreach($config as $key=>$val) {
            $this->$key = $val;
        }
        $this->timeout = 30;
        $this->not_in_params = [
            'api_gate',
            'timeout',
        ];
        $this->init();
    }

    /**
     * @param $type
     * @param $api
     * @param $config
     * @return mixed
     * @throws PaymentException
     */
    public static function factory($type,$api,$config){
        $class = "dungang\\payment\\$type\\$api";
        if (class_exists($class)) {
            return new $class($config);
        }
        throw new PaymentException('API - ' . $class . ' not found');
    }

    public function init(){}

    /**
     * @param array $params
     */
    public function setPrivateParams(array $params)
    {
        $this->except_params = array_merge($this->except_params,$params);
    }

    /**
     * 获取需要签名的参数
     * @return array
     */
    public function getSignParams()
    {
        $params = $this->getAttributes();
        foreach($this->except_params as $name) {
            if (isset($params[$name])) {
                unset($params[$name]);
            }
        }
        return $params;
    }

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
     * 签名
     * @return array
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
     * @param $url
     * @param $isPost
     * @param array $data
     * @param array $options
     * @param array $headers
     * @return mixed
     * @throws \Exception
     */
    public function request($url,$isPost,$data=[],$options=[],$headers=[])
    {
        $ch = curl_init();
        //set url
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_TIMEOUT,$this->timeout);
        //定义options
        if (is_array($options)) {
            foreach ($options as $const => $option) {
                if (defined($const)) {
                    $const_val = constant($const);
                    switch ($const_val) {
                        case CURLOPT_HTTPHEADER:
                        case CURLOPT_POST:
                        case CURLOPT_POSTFIELDS:
                        case CURLOPT_URL:
                            continue;
                        default:
                            curl_setopt($ch,$const_val,$option);
                    }
                }
            }
        }
        //定义头部信息
        if (is_array($headers)) {
            curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        }

        //定义post
        if ($isPost) {
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        }

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