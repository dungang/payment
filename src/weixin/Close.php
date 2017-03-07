<?php
/**
 * Author: dungang
 * Date: 2017/3/7
 * Time: 9:41
 */

namespace dungang\payment\weixin;

/**
 * Class Close 关闭订单
 *
 * 接口链接：https://api.mch.weixin.qq.com/pay/closeorder
 * 以下情况需要调用关单接口：商户订单支付失败需要生成新单号重新发起支付，要对原订单号调用关单，避免重复支付；系统下单后，用户支付超时，系统退出不再受理，避免用户继续，请调用关单接口。
 * 注意：订单生成后不能马上调用关单接口，最短调用时间间隔为5分钟。
 *
 * 不需要证书
 *
 * @package dungang\payment\weixin
 *
 * @property string $out_trade_no 商户系统内部的订单号，请确保在同一商户号下唯一。
 */
class Close extends Base
{
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->method = 'closeorder';
    }
}