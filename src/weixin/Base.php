<?php
/**
 * Author: dungang
 * Date: 2017/3/7
 * Time: 9:01
 */

namespace dungang\payment\weixin;


use dungang\payment\API;
use dungang\payment\PaymentException;

/**
 * Class Base
 * @package dungang\payment\weixin
 *
 * @property string $method 微信支付的接口路径
 * @property string $ssl_cert 证书路径
 * @property string $ssl_key 证书密钥路径
 * @property string $ssl_ca_info 服务CA路径
 * @property string $key 加密密钥
 * @property boolean $use_cert 是否使用证书，默认值 false
 *
 * @property string $appid  微信支付分配的公众账号ID（企业号corpid即为此appId）
 * @property string $mch_id 微信支付分配的商户号
 * @property string $nonce_str 随机字符串，长度要求在32位以内。推荐随机数生成算法
 * @property string $sign 通过签名算法计算得出的签名值，详见签名生成算法
 * @property string $sign_type 商品简单描述，该字段请按照规范传递，具体请见参数规定
 */
class Base extends API
{
    public $key;

    public $useCert = false;

    public function init()
    {
        $this->api_gate = 'https://api.mch.weixin.qq.com/pay/';
        $this->sign_type = 'MD5';
        $this->nonce_str = $this->makeNonceStr();
        $this->setPrivateParams([
            'method',
            'ssl_cert',
            'ssl_key',
            'ssl_ca_info',
            'key',
            'use_cert'
        ]);
    }


    /**
     * @return array
     */
    public function sign()
    {
        $params = $this->getSignParams();
        if (isset($params['sign'])) unset($params['sign']);
        $params = array_filter($params,function($item){
            return !is_numeric($item) && !empty($item);
        });
        ksort($params);
        array_map(function($string){
            return mb_convert_encoding($string,'UTF-8');
        },$params);
        $params['key'] = $this->key;
        $params['sign'] = md5(http_build_str($params));
        return $params;
    }

    /**
     * @return mixed
     */
    public function call()
    {
        $options = [
            'CURLOPT_SSL_VERIFYPEER'=>true,
            'CURLOPT_SSL_VERIFYHOST'=>2, //严格校验
            'CURLOPT_HEADER'=> false,
            'CURLOPT_RETURNTRANSFER'=>true,
        ];
        if ($this->use_cert) {
            $options = array_merge($options,[
                'CURLOPT_SSL_VERIFYPEER'=>false,
                'CURLOPT_SSL_VERIFYHOST' => false,
                'CURLOPT_SSLCERTTYPE'=>'PEM',
                'CURLOPT_SSLCERT'=>$this->ssl_cert,
                'CURLOPT_SSLKEYTYPE'=>'PEM',
                'CURLOPT_SSLKEY'=>$this->ssl_key,
                'CURLOPT_CAINFO'=>$this->ssl_ca_info
            ]);
        }
        $response = $this->request(
            rtrim($this->api_gate ,'/') .'/' . $this->method,
            true,
            $this->toXML($this->sign()),
            $options);
        return $this->parseXML($response);
    }

    /**
     * @param int $length
     * @return string
     */
    public function makeNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return strtoupper($str);
    }

    /**
     * @param $params
     * @return string
     * @throws PaymentException
     */
    public function toXML($params)
    {
        if(!is_array($params)
            || count($params) <= 0)
        {
            throw new PaymentException("Not XML format");
        }

        $xml = "<xml>";
        foreach ($params as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * 将xml转为array
     * @param $xml
     * @return mixed
     * @throws PaymentException
     */
    public function parseXML($xml)
    {
        if(!$xml){
            throw new PaymentException("xml数据异常！");
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

    }

}