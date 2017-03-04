<?php
/**
 * User: dungang
 * Date: 2017/3/4
 * Time: 15:31
 */

namespace dungang\payment\alipay\trade;


use dungang\payment\alipay\Payment;

/**
 * Class Query
 * @package dungang\payment\alipay\trade
 * @property string $out_trade_no 订单支付时传入的商户订单号,和支付宝交易号不能同时为空。 trade_no,out_trade_no如果同时存在优先取trade_no
 * @property string $trade_no 支付宝交易号，和商户订单号不能同时为空
 */
class Query extends Payment
{
    public function init()
    {
        parent::init();
        $this->method = 'alipay.trade.query';
    }
}