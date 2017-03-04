<?php
/**
 * Author: dungang
 * Date: 2017/3/4
 * Time: 16:03
 */

namespace dungang\payment\alipay\trade;


use dungang\payment\alipay\Payment;

/**
 * Class FastPayRefundQuery
 * alipay.trade.fastpay.refund.query (统一收单交易退款查询)
 * 商户可使用该接口查询自已通过alipay.trade.refund提交的退款请求是否执行成功
 * @package dungang\payment\alipay\trade
 * @property string $trade_no 该交易在支付宝系统中的交易流水号。最短 16 位，最长 64 位。和out_trade_no不能同时为空，如果同时传了 out_trade_no和 trade_no，则以 trade_no为准。
 * @property string $out_trade_no 订单支付时传入的商户订单号,和支付宝交易号不能同时为空。 trade_no,out_trade_no如果同时存在优先取trade_no
 * @property string $out_request_no 请求退款接口时，传入的退款请求号，如果在退款请求时未传入，则该值为创建交易时的外部交易号
 */
class FastPayRefundQuery extends Payment
{
    public function init()
    {
        parent::init();
        $this->method = 'alipay.trade.fastpay.refund.query';
    }
}