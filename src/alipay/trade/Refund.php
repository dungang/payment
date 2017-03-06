<?php
/**
 * Author: dungang
 * Date: 2017/3/4
 * Time: 15:39
 */

namespace dungang\payment\alipay\trade;


use dungang\payment\alipay\Payment;

/**
 * Class Refund
 * @package dungang\payment\alipay\trade
 * @property string $out_trade_no 订单支付时传入的商户订单号,不能和 trade_no同时为空。
 * @property string $trade_no 支付宝交易号，和商户订单号不能同时为空
 * @property string $refund_amount 需要退款的金额，该金额不能大于订单金额,单位为元，支持两位小数
 * @property string $refund_reason (可选)退款的原因说明
 * @property string $out_request_no (可选)标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传。
 * @property string $operator_id (可选)商户的操作员编号
 * @property string $store_id (可选)商户的门店编号
 * @property string $terminal_id (可选)商户的终端编号
 *
 */
class Refund extends Payment
{
    public $response = [
        'trade_no', //支付宝交易号
        'out_trade_no', //商家订单号
        'open_id', //买家支付宝用户号，该字段将废弃，不要使用
        'buyer_logon_id', //买家支付宝账号
        'fund_change', //本次退款是否发生了资金变化
        'refund_fee', //退款总金额
        'gmt_refund_pay',//退款支付时间
        'refund_detail_item_list', //退款使用的资金渠道
        'store_name', //请求交易支付中的商户店铺的名称
        'buyer_user_id', //买家在支付宝的用户id
    ];
    public function init()
    {
        parent::init();
        $this->method = 'alipay.trade.refund';
    }
}