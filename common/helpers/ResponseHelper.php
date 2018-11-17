<?php
namespace common\helpers;

// crontab格式解析工具类
class ResponseHelper
{
    // 输出内容信息
    public static function send($code, $message, $data=[])
    {
        $response['code'] = $code;
        $response['message'] = $message;
        $response['data'] = $data;

        echo json_encode($response); exit;
    }

}