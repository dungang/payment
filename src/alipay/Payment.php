<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2017/3/4
 * Time: 13:54
 */

namespace dungang\payment\alipay;


use dungang\payment\API;

/**
 * Class Payment
 * @package dungang\payment\alipay
 *
 * @property string $app_id  支付宝分配给开发者的应用ID
 * @property string $method  接口名称
 * @property string $format  仅支持JSON
 * @property string $charset  请求使用的编码格式，如utf-8,gbk,gb2312等
 * @property string $sign_type  商户生成签名字符串所使用的签名算法类型，目前支持RSA2和RSA，推荐使用RSA2
 * @property string $sign  商户请求参数的签名串，详见签名
 * @property string $timestamp  发送请求的时间，格式"yyyy-MM-dd HH:mm:ss"
 * @property string $version  调用的接口版本，固定为：1.0
 * @property string $app_auth_token  详见应用授权概述
 * @property string $biz_content  请求参数的集合，最大长度不限，除公共参数外所有请求参数都必须放在这个参数中传递，具体参照各产品快速接入文档
 *
 */
abstract class Payment extends API
{
    public function init()
    {
        $this->apiGate = 'https://openapi.alipay.com/gateway.do';
        $this->format = 'json';
        $this->charset = 'utf-8';
        $this->version = '1.0';
        $this->sign_type = 'RSA';
        $this->timestamp = date("Y-m-d H:i:s");
    }

    public function call()
    {
        $result = $this->curl($this->apiGate,$this->charset);
        return json_decode($result);
    }

    public function sign()
    {
        $params = $this->getAttributes();
        if (isset($params['sign'])) unset($params['sign']);
        $this->sign = $this->openSSLSign(http_build_query($params),$this->sign_type);
    }
}