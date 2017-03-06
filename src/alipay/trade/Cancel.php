<?php
/**
 * Author: dungang
 * Date: 2017/3/4
 * Time: 16:01
 */

namespace dungang\payment\alipay\trade;


use dungang\payment\alipay\Payment;

/**
 * Class Cancel
 * alipay.trade.cancel (统一收单交易撤销接口)
 * 支付交易返回失败或支付系统超时，调用该接口撤销交易。如果此订单用户支付失败，支付宝系统会将此订单关闭；如果用户支付成功，支付宝系统会将此订单资金退还给用户。 注意：只有发生支付系统超时或者支付结果未知时可调用撤销，其他正常支付的单如需实现相同功能请调用申请退款API。提交支付交易后调用【查询订单API】，没有明确的支付结果再调用【撤销订单API】。
 * @package dungang\payment\alipay\trade
 * @property string $trade_no 该交易在支付宝系统中的交易流水号。最短 16 位，最长 64 位。和out_trade_no不能同时为空，如果同时传了 out_trade_no和 trade_no，则以 trade_no为准。
 * @property string $out_trade_no 订单支付时传入的商户订单号,和支付宝交易号不能同时为空。 trade_no,out_trade_no如果同时存在优先取trade_no
 */
class Cancel extends Payment
{
    public $response = [
        'trade_no', //支付宝交易号
        'out_trade_no', //商户订单号
        'retry_flag', //是否需要重试
        'action' //本次撤销触发的交易动作 close：关闭交易，无退款 refund：产生了退款
    ];
    public function init()
    {
        parent::init();
        $this->method = 'alipay.trade.cancel';
    }
}