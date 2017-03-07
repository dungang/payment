<?php
/**
 * Author: dungang
 * Date: 2017/3/7
 * Time: 9:01
 */

namespace dungang\payment\weixin;


use dungang\payment\API;

/**
 * Class Base
 * @package dungang\payment\weixin
 *
 * @property string method 微信支付的接口路径
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

    public function init()
    {
        $this->apiGate = 'https://api.mch.weixin.qq.com/pay/';
        $this->sign_type = 'MD5';
    }

    public function sign()
    {
        $params = $this->getAttributes();
        if (isset($params['sign'])) unset($params['sign']);
        $params = array_filter($params,function($item){
            return !is_numeric($item) && !empty($item);
        });
        ksort($params);
        array_map(function($string){
            return mb_convert_encoding($string,'UTF-8');
        },$params);
        $params['key'] = $this->key;
        $this->sign = md5(http_build_str($params));
    }

    public function call()
    {
        // TODO: Implement call() method.
    }

    public function makeNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return strtoupper($str);
    }

}