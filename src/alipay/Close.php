<?php
/**
 * Author: dungang
 * Date: 2017/3/4
 * Time: 16:04
 */

namespace dungang\payment\alipay;


/**
 * Class Close
 * alipay.trade.close (统一收单交易关闭接口)
 *
 * 用于交易创建后，用户在一定时间内未进行支付，可调用该接口直接将未付款的交易进行关闭。
 * @package dungang\payment\alipay\trade
 * @property string $trade_no 该交易在支付宝系统中的交易流水号。最短 16 位，最长 64 位。和out_trade_no不能同时为空，如果同时传了 out_trade_no和 trade_no，则以 trade_no为准。
 * @property string $out_trade_no 订单支付时传入的商户订单号,和支付宝交易号不能同时为空。 trade_no,out_trade_no如果同时存在优先取trade_no
 * @property string $operator_id 操作员id
 * @property string $notify_url 支付宝服务器主动通知商户服务器里指定的页面http/https路径
 */
class Close extends Base
{
    public $response_params = [
        'out_trade_no', //商户订单号
        'trade_no' //支付宝交易号
    ];

    public function init()
    {
        parent::init();
        $this->method = 'alipay.trade.close';
    }
}