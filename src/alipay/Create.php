<?php
/**
 * Author: dungang
 * Date: 2017/3/4
 * Time: 16:02
 */

namespace dungang\payment\alipay;


/**
 * Class Create
 * alipay.trade.create (统一收单交易创建接口)
 * 商户通过该接口进行交易的创建下单
 * @package dungang\payment\alipay\trade
 * @property string $out_trade_no 商户订单号,64个字符以内、可包含字母、数字、下划线；需保证在商户端不重复
 * @property string $seller_id (可选)如果该值为空，则默认为商户签约账号对应的支付宝用户ID
 * @property string $total_amount (可选)订单总金额，单位为元，精确到小数点后两位，取值范围[0.01,100000000]。 如果同时传入【可打折金额】和【不可打折金额】，该参数可以不用传入； 如果同时传入了【可打折金额】，【不可打折金额】，【订单总金额】三者，则必须满足如下条件：【订单总金额】=【可打折金额】+【不可打折金额】
 * @property string $discountable_amount (可选)参与优惠计算的金额，单位为元，精确到小数点后两位，取值范围[0.01,100000000]。 如果该值未传入，但传入了【订单总金额】和【不可打折金额】，则该值默认为【订单总金额】-【不可打折金额】
 * @property string $undiscountable_amount (可选)不参与优惠计算的金额，单位为元，精确到小数点后两位，取值范围[0.01,100000000]。如果该值未传入，但传入了【订单总金额】和【可打折金额】，则该值默认为【订单总金额】-【可打折金额】
 * @property string $buyer_logon_id (可选)买家支付宝账号，和buyer_id不能同时为空
 * @property string $subject  订单标题
 * @property string $body (可选)订单描述
 * @property string $buyer_id (可选)买家的支付宝唯一用户号（2088开头的16位纯数字）,和buyer_logon_id不能同时为空
 * @property string $goods_detail (可选)订单包含的商品列表信息，Json格式，其它说明详见商品明细说明
 * @property string $operator_id (可选)商户操作员编号
 * @property string $store_id (可选)商户门店编号
 * @property string $terminal_id (可选)商户机具终端编号
 * @property string $extend_params (可选)业务扩展参数
 * @property string $timeout_express (可选)该笔订单允许的最晚付款时间，逾期将关闭交易。取值范围：1m～15d。m-分钟，h-小时，d-天，1c-当天（1c-当天的情况下，无论交易何时创建，都在0点关闭）。 该参数数值不接受小数点， 如 1.5h，可转换为 90m
 * @property string $royalty_info (可选)描述分账信息，Json格式，其它说明详见分账说明
 * @property string $alipay_store_id (可选)支付宝的店铺编号
 * @property string $sub_merchant (可选)二级商户信息,当前只对特殊银行机构特定场景下使用此字段
 * @property string $merchant_order_no (可选)商户原始订单号，最大长度限制32位
 * @property string $notify_url 支付宝服务器主动通知商户服务器里指定的页面http/https路径
 */
class Create extends Base
{
    public $response_params = [
        'out_trade_no', //商户订单号
        'trade_no' //支付宝交易号
    ];
    public function init()
    {
        parent::init();
        $this->method = 'alipay.trade.create';
    }
}