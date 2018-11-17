<?php
/**
 * Created by PhpStorm.
 * User: youertao
 * Date: 16/4/12
 * Time: 下午3:15
 */

namespace common\helpers;

use Yii;

class HttpHelper
{
    private static $startAt;    # 进程开始时间


    /**
     * CURL_POST
     * @param $url  请求地址
     * @param array $params 参数内容
     * @param int $timeout  请求超时
     * @return mixed
     */
    public static function post($url, $params = array(),  $timeout = 30)
    {
        self::$startAt = microtime(true);
        $ch = curl_init($url);
        $options = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_SSL_VERIFYPEER => false, //不进行ssl证书验证
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        self::$finishAt = microtime(true);
        self::sendDataToLogdb($url, "POST", $params, $result, $info['http_code']);
        return $result;
    }

    /**
     * CURL_GET
     * @param $url
     * @param array $params
     * @param int $timeout  请求超时
     * @return mixed
     */
    public static function get($url, $params = array(), $timeout = 30)
    {
        self::$startAt = microtime(true);

        $query = http_build_query($params);
        $ch = curl_init($url . '?' . $query);
        $options = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_SSL_VERIFYPEER => false, //不进行ssl证书验证
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        self::$finishAt = microtime(true);
        self::sendDataToLogdb($url . '?' . $query, "GET", [], $result, $info['http_code']);
        return $result;
    }

    /**
     *  记录日志信息
     *
     * @param $url      请求地址
     * @param $method   请求方式
     * @param $params   请求参数
     * @param $response 执行结果
     * @param $responseCode 返回状态
     */
    public static function sendDataToLogdb($url, $method, $params, $response, $responseCode) {
        $data = array(
            "request_id" => Logdb::getReqId(),
            "caller" => Logdb::getCaller(),
            "client_ip" => Yii::$app->params['log_db_const']['params']['client_ip'] ?? '',
            "header" => '',
            "request_method" => $method,
            "url" => $url,
            "request_params" => json_encode($params, JSON_UNESCAPED_UNICODE),
            "response_code" => $responseCode,
            "response_data" => $response,
            "request_time" => date('Y-m-d H:i:s', self::$startAt),
            "request_microtime" => self::$startAt,
            "spend_microtime" => bcsub(self::$finishAt, self::$startAt,4),
        );
        Logdb::log('apilog', $data);
    }
}
