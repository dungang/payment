<?php
/**
 * Author: dungang
 * Date: 2017/3/7
 * Time: 9:43
 */

namespace dungang\payment\weixin;

/**
 * Class Refund 申请退款
 *
 * 接口链接：https://api.mch.weixin.qq.com/secapi/pay/refund
 *
 * 当交易发生之后一段时间内，由于买家或者卖家的原因需要退款时，卖家可以通过退款接口将支付款退还给买家，微信支付将在收到退款请求并且验证成功之后，按照退款规则将支付款按原路退到买家帐号上。
 * 注意：
 * 1、交易时间超过一年的订单无法提交退款；
 * 2、微信支付退款支持单笔交易分多次退款，多次退款需要提交原支付订单的商户订单号和设置不同的退款单号。总退款金额不能超过用户实际支付金额。 一笔退款失败后重新提交，请不要更换退款单号，请使用原商户退款单号。
 *
 * 请求需要双向证书。 详见证书使用
 *
 * @package dungang\payment\weixin
 *
 * @property string $device_info  	自定义参数，可以为终端设备号(门店号或收银设备ID)，PC网页或公众号内支付可以传"WEB"
 * @property string $transaction_id 微信的订单号，建议优先使用
 * @property string $out_trade_no 商户系统内部的订单号，请确保在同一商户号下唯一。
 * @property string $out_refund_no 商户系统内部的退款单号，商户系统内部唯一，同一退款单号多次请求只退一笔
 * @property string $total_fee 订单总金额，单位为分，只能为整数，详见支付金额
 * @property string $refund_fee 退款总金额，订单总金额，单位为分，只能为整数，详见支付金额
 * @property string $refund_fee_type 货币类型，符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
 * @property string $op_user_id 操作员帐号, 默认为商户号
 * @property string $refund_account 仅针对老资金流商户使用, REFUND_SOURCE_UNSETTLED_FUNDS---未结算资金退款（默认使用未结算资金退款）,REFUND_SOURCE_RECHARGE_FUNDS---可用余额退款
 */
class Refund extends Base
{
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->method = 'refund';
    }
}