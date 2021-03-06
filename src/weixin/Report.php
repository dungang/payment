<?php
/**
 * Author: dungang
 * Date: 2017/3/7
 * Time: 10:04
 */

namespace dungang\payment\weixin;

/**
 * Class Report
 * @package dungang\payment\weixin
 *
 * @property string $interface_url 报对应的接口的完整URL，
 * 类似：
 * https://api.mch.weixin.qq.com/pay/unifiedorder
 * 对于刷卡支付，为更好的和商户共同分析一次业务行为的整体耗时情况，对于两种接入模式，请都在门店侧对一次刷卡支付进行一次单独的整体上报，上报URL指定为：
 * https://api.mch.weixin.qq.com/pay/micropay/total
 * 关于两种接入模式具体可参考本文档章节：刷卡支付商户接入模式
 * 其它接口调用仍然按照调用一次，上报一次来进行。
 * @property string $execute_time 接口耗时情况，单位为毫秒
 * @property string $return_code 此字段是通信标识，非交易标识，交易是否成功需要查看trade_state来判断
 * @property string $return_msg 返回信息，如非空，为错误原因;签名失败;参数格式校验错误
 * @property string $result_code SUCCESS/FAIL
 * @property string $err_code ORDERNOTEXIST—订单不存在;SYSTEMERROR—系统错误
 * @property string $err_cde_des 结果信息描述
 * @property string $out_trade_no 商户系统内部的订单号,商户可以在上报时提供相关商户订单号方便微信支付更好的提高服务质量。
 * @property string $user_ip 发起接口调用时的机器IP
 * @property string $time 系统时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。其他详见时间规则
 *
 */
class Report extends Base
{
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->method = 'report';
    }
}