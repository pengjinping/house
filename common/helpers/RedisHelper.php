<?php
/**
 * Created by PhpStorm.
 * User: zhouping
 * Date: 17/1/10
 * Time: 下午3:30
 */

namespace common\helpers;

use Yii;
use yii\redis\Cache;

class RedisHelper extends Cache
{
    const CACHE_SECOND = 1;     //1秒
    const CACHE_MINUTE = 60;    //分
    const CACHE_HOUR = 3600;    //时
    const CACHE_DAY = 86400;    //天
    const CACHE_WEEK = 604800;  //周

    public static $isopen = true;   // 缓存开关
    protected static $cache = null;

    /**
     * @return \yii\redis\Connection
     */
    public static function getCacheObj()
    {
        if (self::$cache === null) {
            self::$cache = Yii::$app->redis;
        }
        return self::$cache;
    }
    public static function select($inx)
    {
        if (self::$cache === null) {
            self::$cache = Yii::$app->redis;
        }
        self::$cache->select($inx);
    }

    /**
     * redis计数器-增加
     *
     * @param $key
     * @param $expire
     *
     * @return array|bool|null|string
     */
    public static function incr($key, $expire=10)
    {
        $count = self::getCacheObj()->executeCommand('INCR', [$key]);
        if ($count == 1) {
            self::getCacheObj()->executeCommand('EXPIRE', [$key, $expire]);
        }

        return $count;
    }

    /**
     * redis计数器-减少
     *
     * @param $key
     *
     * @return array|bool|null|string
     */
    public static function decr($key)
    {
        return self::getCacheObj()->executeCommand('DECR', [$key]);
    }

    /**
     * 设置缓存
     *
     * @param $key
     * @param $value
     * @param $expire
     *
     * @return array|bool|null|string
     */
    public static function setCache($key, $value, $expire)
    {
        return self::getCacheObj()->executeCommand('SET', [$key, $value, 'EX', $expire]);
    }

    /**
     * 设置缓存  不覆盖原值
     * @param $key
     * @param $value
     * @param $expire
     *
     * @return bool
     */
    public static function setnx($key, $value, $expire)
    {
        return self::getCacheObj()->executeCommand('SET', [$key, $value, 'EX', $expire, 'NX']);
    }

    /**
     * 读取缓存
     *
     * @param $key
     *
     * @return array|bool|null|string
     */
    public static function getCache($key)
    {
        if(self::$isopen == false){
            return false;
        }
        return self::getCacheObj()->executeCommand('GET', [$key]);
    }

    /**
     * 删除缓存
     *
     * @param $key
     *
     * @return array|bool|null|string
     */
    public static function delCache($key)
    {
        return self::getCacheObj()->executeCommand('DEL', [$key]);
    }

    /**
     * 入队列
     *
     * @param $queue
     * @param $value
     *
     * @return array|bool|null|string
     */
    public static function push($queue, $value)
    {
        return self::getCacheObj()->executeCommand('LPUSH', [$queue, $value]);
    }
    public static function RPush($queue, $value)
    {
        return self::getCacheObj()->executeCommand('RPUSH', [$queue, $value]);
    }

    /**
     * 出队列
     *
     * @param $queue
     *
     * @return array|bool|null|string
     */
    public static function pop($queue)
    {
        if(self::$isopen == false){
            return false;
        }
        return self::getCacheObj()->executeCommand('LPOP', [$queue]);
    }

    /**
     * 队列长度
     *
     * @param $queue
     *
     * @return int
     */
    public static function length($queue): int
    {
        return (int)self::getCacheObj()->executeCommand('LLEN', [$queue]);
    }

    /**
     * 获取队列数据
     *
     * @param string $queue
     * @param int $startIndex
     * @param int $endIndex
     *
     * @return array|bool|null|string
     */
    public static function listRange(string $queue, $startIndex = 0, $endIndex = -1)
    {
        return self::getCacheObj()->executeCommand('LRANGE', [$queue, $startIndex, $endIndex]);
    }

    /**
     * 裁剪list
     *
     * @param string $queue
     * @param int $startIndex
     * @param int $endIndex
     *
     * @return array|bool|null|string
     */
    public static function listTrim(string $queue, $startIndex = 0, $endIndex = 0)
    {
        return self::getCacheObj()->executeCommand('LTRIM', [$queue, $startIndex, $endIndex]);
    }

    /**
     * @param       $key
     * @param array $func
     * @param array $params
     * @param int $expire
     *
     * @return array|bool|mixed|null|string
     */
    public static function getForCache($key, $func = [], $params = [], $expire = 60)
    {
        $value = self::$isopen ? self::getCache($key) : false;
        if ( empty($value) && !empty($func) ) {
            if ($value = call_user_func_array($func, $params) ) {
                self::setCache($key, $value, $expire);
            }
        }

        return $value;
    }

}
