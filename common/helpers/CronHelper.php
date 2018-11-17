<?php
namespace common\helpers;

// crontab格式解析工具类
class CronHelper
{
    /**
     * 格式化crontab格式字符串
     * @param  string $cronstr
     * @return string 返回符合格式的时间
     */
    public static function formatToDate($cronstr)
    {
        if ( !static::check($cronstr) ) {
            throw new \Exception("格式错误: $cronstr", 601);
        }

        $tags = preg_split('#\s+#', $cronstr);
        $crons = [
            'minutes' => static::parseTag($tags[0], 0, 59), //分钟
            'hours'   => static::parseTag($tags[1], 0, 23), //小时
            'day'     => static::parseTag($tags[2], 1, 31), //一个月中的第几天
            'month'   => static::parseTag($tags[3], 1, 12), //月份
            'week'    => static::parseTag($tags[4], 0, 6), // 星期
        ];

        // 解析当前时间
        $nowtimes = preg_split('#\s+#', date('H i m d w Y t', time() + 60));
        while(true){
            $issign = 1;

            if( !in_array($nowtimes[2], $crons['month']) ){         // 月
                $nowtimes[2] = self::getNextdata($crons['month'], $nowtimes[2], 12);
                $nowtimes[3] = $crons['day'][0];
                $nowtimes[0] = $crons['hours'][0];
                $nowtimes[1] = $crons['minutes'][0];
                $issign = 2;
            }
            if($issign == 1 && !in_array($nowtimes[3], $crons['day']) ){    // 日
                $nowtimes[3] = self::getNextdata($crons['day'], $nowtimes[3], $nowtimes[6]);
                $nowtimes[0] = $crons['hours'][0];
                $nowtimes[1] = $crons['minutes'][0];
                $issign = 3;
            }
            if($issign == 1 && !in_array($nowtimes[4], $crons['week']) ){   // 周
                $nowtimes[4] = $nowtimes[4] < 6 ? $nowtimes[4] + 1 : 0;
                $nowtimes[3] += 1;
                $nowtimes[0] = $crons['hours'][0];
                $nowtimes[1] = $crons['minutes'][0];
                $issign = 4;
            }
            if($issign == 1 && !in_array($nowtimes[0], $crons['hours']) ){   // 时
                $nowtimes[0] = self::getNextdata($crons['hours'], $nowtimes[0], 24);
                $nowtimes[1] = $crons['minutes'][0];
                $issign = 5;
            }
            if($issign == 1 && !in_array($nowtimes[1], $crons['minutes']) ){  // 分
                $nowtimes[1] = self::getNextdata($crons['minutes'], $nowtimes[1], 60);
                $issign = 6;
            }

            $itime = mktime($nowtimes[0], $nowtimes[1], 0, $nowtimes[2], $nowtimes[3], $nowtimes[5]);
            $nowtimes = preg_split('#\s+#', date('H i m d w Y t', $itime));
            if($issign == 1){
                return date('Y-m-d H:i:s', $itime);
            }
        }
    }

    private static function getNextdata($datas, $nowval, $max=60){
        foreach ($datas as $data){
            if($data >= $nowval) return $data;
        }
        return $datas[0] + $max;
    }


    /**
     * 检查crontab格式是否支持
     * @param  string $cronstr
     * @return boolean true|false
     */
    private static function check($cronstr)
    {
        $cronstr = trim($cronstr);

        if (count(preg_split('#\s+#', $cronstr)) !== 5) {
            return false;
        }

        $reg = '#^(\*(/\d+)?|\d+([,\d\-]+)?)\s+(\*(/\d+)?|\d+([,\d\-]+)?)\s+(\*(/\d+)?|\d+([,\d\-]+)?)\s+(\*(/\d+)?|\d+([,\d\-]+)?)\s+(\*(/\d+)?|\d+([,\d\-]+)?)$#';
        if ( !preg_match($reg, $cronstr) ) {
            return false;
        }

        return true;
    }

    /**
     * 解析元素
     * @param  string $tag  元素标签
     * @param  integer $tmin 最小值
     * @param  integer $tmax 最大值
     * @throws \Exception
     */
    protected static function parseTag($tag, $tmin, $tmax)
    {
        if( $tag == '*' ){
            $dateList =  range($tmin, $tmax);
        }else if (false !== strpos($tag, '/')) {
            $tmp = explode('/', $tag);
            $step = isset($tmp[1]) ? $tmp[1] : 1;
            $dateList = range($tmin, $tmax, $step);
        } else if (false !== strpos($tag, '-')) {
            list($min, $max) = explode('-', $tag);
            if ($min > $max) { list($min, $max) = [$max, $min]; }
            $dateList = range($min, $max, 1);
        }else if (false !== strpos($tag, ',')) {
            $dateList = explode(',', $tag);
        }else {
            $dateList = [$tag];
        }

        // 越界判断
        foreach ($dateList as $num) {
            if ($num < $tmin || $num > $tmax) {
                throw new \Exception('数值越界');
            }
        }

        sort($dateList);
        return $dateList;
    }

}