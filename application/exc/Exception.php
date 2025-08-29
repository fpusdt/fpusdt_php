<?php

/**
 * Date: 2025年8月28日
 * 有问题联系 纸飞机(Telegram): https://t.me/king_orz

 * 温馨提示：接受各种代码定制
 */

namespace app\exc;


use think\Response;

class Exception
{
    protected $msg = '错误消息';
    protected $code = 0;  //TOAST自动弹出消息
    const NOT_LOGIN = 401; //未登录自动弹框提醒
    const NOT_AUTHORIZE = 403; //
    const IGNORE = -1;
    public function __construct($msg, $code = 0, $status_code = 200)
    {
        $this->msg = $msg;
        $this->code = $code;
        $this->send($status_code);
    }
    protected function send($code = 200)
    {
        $data = [
            'code' => $this->code,
            'msg' => $this->msg,
            'data' => null,
            'time' => time()
        ];
        $response = Response::create($data, 'json', $code);
        throw new \think\exception\HttpResponseException($response);
    }
}
