<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2017/3/4
 * Time: 13:54
 */

namespace dungang\payment\alipay;


use dungang\payment\API;
use dungang\payment\PaymentException;

/**
 * Class Payment
 * @package dungang\payment\alipay
 *
 * @property string $rsa_key  私钥路径
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
class Base extends API
{
    const WAIT_BUYER_PAY = 'WAIT_BUYER_PAY'; //交易创建，等待买家付款
    const TRADE_CLOSE = 'TRADE_CLOSE';  //未付款交易超时关闭，或支付完成后全额退款
    const TRADE_SUCCESS = 'TRADE_SUCCESS'; //交易支付成功
    const TRADE_FINISHED = 'TRADE_FINISHED'; //交易结束，不可退款

    /**
     * @var array response params
     */
    protected $response_arams = [];

    public function init()
    {
        $this->response_arams = array_merge([
            'code', //网关返回码,详见文档
            'msg', //网关返回码描述,详见文档
            'sub_code', //业务返回码,详见文档
            'sub_msg', //业务返回码描述,详见文档
            'sign' //签名,详见文档
        ],$this->response_arams);
        $this->api_gate = 'https://openapi.alipay.com/gateway.do';
        $this->format = 'json';
        $this->charset = 'utf-8';
        $this->version = '1.0';
        $this->sign_type = 'RSA';
        $this->timestamp = date("Y-m-d H:i:s");
        $this->setPrivateParams([
            'private_key'
        ]);
    }

    /**
     * @return mixed
     * @throws PaymentException
     */
    public function call()
    {
        $response = $this->request($this->api_gate,true,$this->sign(),[
            'CURLOPT_FAILONERROR'=>false,
            'CURLOPT_RETURNTRANSFER'=>true,
            'CURLOPT_SSL_VERIFYPEER'=>false,
        ],['content-type: application/x-www-form-urlencoded;charset=' . $this->charset]);

        $response_key = str_replace('.','_',$this->method) . '_response';
        $response = json_decode($response);
        if (empty($response[$response_key])) {
            throw new PaymentException('Response is empty!');
        }
        $result = $response[$response_key];
        if ($this->verifySign($result)) {
            if ($result['code'] > 10000) {
                throw new PaymentException($result['msg']);
            }
            return $result;
        }
        throw new PaymentException('Sign not match');
    }

    /**
     * 签名
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
        array_map([$this,'convertCharset'],$params);
        $params['sign'] = $this->openSSLSign(http_build_query($params),$this->sign_type);
        return $params;
    }

    /**
     * 转换字符串编码
     * @param $string
     * @return mixed|string
     */
    public function convertCharset($string)
    {
        return mb_convert_encoding($string,$this->charset);
    }

    /**
     *  验签
     * @param array $response 通知响应结果
     * @param bool $sync 是否同步验签
     * @return bool
     */
    public function verifySign($response,$sync=true)
    {
        return $sync
            ? $this->syncVerifySign($response)
            : $this->asyncVerifySign($response);
    }

    /**
     * 同步返回前面
     * @param $response
     * @return bool
     * @throws PaymentException
     */
    protected function syncVerifySign($response)
    {
        if (isset($response['sign'])) {
            $remote_sign = $response['sign'];
            unset($response['sign']);
            $local_sign = $this->openSSLSign(json_encode($response,JSON_UNESCAPED_UNICODE),$this->sign_type);
            return strcmp($remote_sign,$local_sign)==0;
        }
        throw new PaymentException('Response lost sign');
    }

    /**
     * @param $response
     * @return bool
     * @throws PaymentException
     */
    public function asyncVerifySign($response)
    {
        if (isset($response['sign'])) {
            $remote_sign = $response['sign'];
            unset($response['sign']);
            if (isset($response['sign_type']))
                unset($response['sign_type']);
            ksort($response);
            $local_sign = $this->openSSLSign(http_build_query($response),$this->sign_type);
            return strcmp($remote_sign,$local_sign)==0;
        }
        throw new PaymentException('Response lost sign');
    }

    /**
     * @param $data string
     * @param $signType string
     * @return string
     * @throws PaymentException
     */
    public function openSSLSign($data,$signType='RSA') {
        if (file_exists($this->rsa_key)) {
            throw new PaymentException('Payment Private key  not found');
        }
        $keyContent = file_get_contents($this->rsa_key);
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
}