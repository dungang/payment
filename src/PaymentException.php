<?php
/**
 * Author: dungang
 * Date: 2017/3/6
 * Time: 16:31
 */

namespace dungang\payment;


class PaymentException extends \Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'PaymentException';
    }

//    /**
//     * 获取异常错误信息
//     * @return string
//     */
//    public function errorMessage()
//    {
//        return $this->getMessage();
//    }
}