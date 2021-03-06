<?php
/**
 * Author: dungang
 * Date: 2017/3/7
 * Time: 9:20
 */

namespace dungang\payment\weixin;

/**
 * Class Create 统一下单
 * URL地址：https://api.mch.weixin.qq.com/pay/unifiedorder
 * 除被扫支付场景以外，商户系统先调用该接口在微信支付服务后台生成预支付交易单，
 * 返回正确的预支付交易回话标识后再按扫码、JSAPI、APP等不同场景生成交易串调起支付。
 * @package dungang\payment\weixin
 *
 * @property string $device_info  	自定义参数，可以为终端设备号(门店号或收银设备ID)，PC网页或公众号内支付可以传"WEB"
 * @property string $body 商品简单描述，该字段请按照规范传递，具体请见参数规定
 * @property string $detail 商品详细列表，使用Json格式，传输签名前请务必使用CDATA标签将JSON文本串保护起来。
 * @property string $attach 附加数据，在查询API和支付通知中原样返回，可作为自定义参数使用。
 * @property string $out_trade_no 商户系统内部订单号，要求32个字符内、且在同一个商户号下唯一。 详见商户订单号
 * @property string $fee_type 符合ISO 4217标准的三位字母代码，默认人民币：CNY，详细列表请参见货币类型
 * @property string $total_fee 订单总金额，单位为分，详见支付金额
 * @property string $spbill_create_ip APP和网页支付提交用户端ip，Native支付填调用微信支付API的机器IP。
 * @property string $time_start 订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。其他详见时间规则
 * @property string $time_expire 订单失效时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。其他详见时间规则注意：最短失效时间间隔必须大于5分钟
 * @property string $goods_tag 商品标记，使用代金券或立减优惠功能时需要的参数，说明详见代金券或立减优惠
 * @property string $notify_url 异步接收微信支付结果通知的回调地址，通知url必须为外网可访问的url，不能携带参数。
 * @property string $trade_type 取值如下：JSAPI，NATIVE，APP等，说明详见参数规定
 * @property string $product_id trade_type=NATIVE时（即扫码支付），此参数必传。此参数为二维码中包含的商品ID，商户自行定义。
 * @property string $limit_pay 上传此参数no_credit--可限制用户不能使用信用卡支付
 * @property string $openid trade_type=JSAPI时（即公众号支付），此参数必传，此参数为微信用户在商户对应appid下的唯一标识。openid如何获取，可参考【获取openid】。企业号请使用【企业号OAuth2.0接口】获取企业号内成员userid，再调用【企业号userid转openid接口】进行转换
 */
class Create extends Base
{

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->method = 'unifiedorder';
    }
}