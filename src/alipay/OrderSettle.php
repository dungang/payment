<?php
/**
 * Author: dungang
 * Date: 2017/3/4
 * Time: 16:05
 */

namespace dungang\payment\alipay;

/**
 * Class OrderSettle
 * alipay.trade.order.settle (统一收单交易结算接口)
 * 用于在线下场景交易支付后，进行结算
 * @package dungang\payment\alipay\trade
 * @property string $out_request_no 结算请求流水号 开发者自行生成并保证唯一性
 * @property string $trade_no 支付宝订单号
 * @property string $royalty_parameters 分账明细信息
 * @property string $operator_id 操作员id
 */
class OrderSettle extends Base
{
    public $responseParams = [
        'trade_no' //支付宝交易号
    ];
    public function init()
    {
        parent::init();
        $this->method = 'alipay.trade.order.settle';
    }
}