<?php
/**
 * User: dungang
 * Date: 2017/3/4
 * Time: 15:31
 */

namespace dungang\payment\alipay;


/**
 * Class Query
 * @package dungang\payment\alipay\trade
 * @property string $out_trade_no 订单支付时传入的商户订单号,和支付宝交易号不能同时为空。 trade_no,out_trade_no如果同时存在优先取trade_no
 * @property string $trade_no 支付宝交易号，和商户订单号不能同时为空
 */
class Query extends Base
{
    public $responseParams = [
        'trade_no', //支付宝交易号
        'out_trade_no', //商家订单号
        'open_id', //买家支付宝用户号，该字段将废弃，不要使用
        'buyer_logon_id', //买家支付宝账号
        'trade_status', //交易状态：WAIT_BUYER_PAY（交易创建，等待买家付款）、TRADE_CLOSED（未付款交易超时关闭，或支付完成后全额退款）、TRADE_SUCCESS（交易支付成功）、TRADE_FINISHED（交易结束，不可退款）
        'total_amount', //交易的订单金额，单位为元，两位小数。该参数的值为支付时传入的total_amount
        'receipt_amount', //实收金额，单位为元，两位小数。该金额为本笔交易，商户账户能够实际收到的金额
        'buyer_pay_amount', //买家实付金额，单位为元，两位小数。该金额代表该笔交易买家实际支付的金额，不包含商户折扣等金额
        'point_amount', //积分支付的金额，单位为元，两位小数。该金额代表该笔交易中用户使用积分支付的金额，比如集分宝或者支付宝实时优惠等
        'invoice_amount', //交易中用户支付的可开具发票的金额，单位为元，两位小数。该金额代表该笔交易中可以给用户开具发票的金额
        'send_pay_date', //本次交易打款给卖家的时间
        'alipay_store_id', //支付宝店铺编号
        'store_id', //商户门店编号
        'terminal_id', //商户机具终端编号
        'fund_bill_list', //交易支付使用的资金渠道
        'store_name', //请求交易支付中的商户店铺的名称
        'buyer_user_id', //买家在支付宝的用户id
        'discount_goods_detail', //本次交易支付所使用的单品券优惠的商品优惠信息
        'industry_sepc_detail', //行业特殊信息（例如在医保卡支付业务中，向用户返回医疗信息）。
        'voucher_detail_list' //本交易支付时使用的所有优惠券信息
    ];
    public function init()
    {
        parent::init();
        $this->method = 'alipay.trade.query';
    }
}