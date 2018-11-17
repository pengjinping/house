<?php
namespace common\helpers;

class StringHelper
{
    /**
     * @param $prefix
     *
     * @return string
     */
    public static function generateSn($prefix = '')
    {
        $str = substr(YII_BEGIN_TIME, 11, 3) . strtoupper(substr(uniqid(), 10, 3));

        return $prefix . date('ymdHis') . $str;
    }

    /**
     * 信息打码，点击显示
     * @param $content
     * @param integer $type 类型：1-手机，2-身份证，3-银行卡，4-姓名
     * @return string
     */
    public static function hidToShow($content, $type)
    {
        $str = <<<STR
<span>%s</span><span class="hidden">%s</span>&nbsp;&nbsp;
<span class="hidtoshow glyphicon glyphicon-eye-open" style="cursor:pointer;font-size:10px;" 
    onclick='javascript:$(this).prev().prev().addClass("hidden");$(this).prev().removeClass("hidden");'></span>
STR;

        $formatContent = $content;
        if ($type == 1) {
            $formatContent = self::formatPhone($content);
        } elseif ($type == 2) {
            $formatContent = self::formatIdentity($content);
        } elseif ($type == 3) {
            $formatContent = self::formatBankCard($content);
        } elseif ($type == 4) {
            $formatContent = self::formatUserName($content);
        }

        return sprintf($str, $formatContent, $content);
    }

    /**
     * 姓名安全显示
     *
     * @param $userName
     *
     * @return mixed
     */
    public static function formatUserName($userName)
    {
        mb_internal_encoding('UTF-8');
        if (mb_strlen($userName) > 2) {
            $prefix = '**';
        } else {
            $prefix = '*';
        }

        return $prefix . mb_substr($userName, -1);
    }

    /**
     * 身份证屏蔽中间年月日
     *
     * @param $identity
     *
     * @return mixed
     */
    public static function formatIdentity($identity)
    {
        $startInx = strlen($identity) == 18 ? 6 : 4;
        return substr_replace($identity, '********', $startInx, 8);
    }

    /**
     * 手机号安全显示
     *
     * @param     $phone
     * @param int $type 安全类型 0-默认（屏蔽中间4位）
     *
     * @return mixed
     */
    public static function formatPhone($phone, $type = 0)
    {
        if($type == 1) {
            return '****' . substr($phone, -4);
        }else if ($type == 2) {
            return '尾号' . substr($phone, -4);
        }else{
            return substr_replace($phone, '****', 3, 4);
        }
    }

    /**
     * 银行卡安全显示
     *
     * @param     $cardNum
     * @param int $type 安全类型 0-默认（只显示最后四位）
     *
     * @return string
     */
    public static function formatBankCard($cardNum, $type = 0)
    {
        if($type == 1) {
            return '**** **** **** **** ' . substr($cardNum, -4);
        }else if ($type == 2) {
            return '尾号' . substr($cardNum, -4);
        }else{
            return substr_replace($cardNum, '**** **** ****', 5, 12);
        }
    }
}